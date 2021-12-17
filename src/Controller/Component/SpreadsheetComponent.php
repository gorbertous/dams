<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
//use PhpOffice\PhpSpreadsheet\Helper;
use voku\helper\URLify;

setlocale(LC_ALL, 'en_US.UTF8');
setlocale(LC_CTYPE, 'en_US.UTF8');

class SpreadsheetComponent extends Component
{

    var $sheet_table = [// for CSV export
        "CR"  => "sme",
        "A1"  => "sme",
        "A2"  => "transactions",
        "A3"  => "guarantees",
        "A4"  => "micro_credit_nb",
        "B"   => "included_transactions",
        "I1"  => "re_performing_start",
        "I2"  => "re_performing_end",
        "D"   => "expired_transactions",
        "E"   => "excluded_transactions",
        "H"   => "transactions",
        "PD"  => "pdlr_transactions",
        "LR"  => "pdlr_transactions",
        "IR"  => "initial_rating",
        "EP"  => "expired_to_performing",
        "EP"  => "expired_to_performing",
        "C"   => "defaulted",
        "S1"  => "impact_data",
        "S2"  => "impact_data_s2",
        "R"   => "recoveries",
        "GGE" => "transactions",
        "IR"  => "transactions",
        "SU"  => "sampling_information",
        "TRS" => "transactions",
        "MS"  => "pdlr_transactions",
        "EP"  => "Expired_Performing",
        "TBE" => "transactions",
        "A21" => 'subtransactions',
        "B1"  => 'included_subtransactions',
    ];

    public function countSheets($filepath)
    {
        $num = 0;
        $reader = IOFactory::createReaderForFile($filepath);
        $sheets = $reader->listWorksheetNames($filepath);
        $num = count($sheets);
        return $num;
    }

    /**
     * Remove accent in a Excel file using PHPExcel Vendor
     * @param $file - file array to use
     * @param $filepath - filepath
     * @param bool $debug - debug some information and display a die
     * @return bool - true if the file is converted
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function removeAccentOnExcel($file, $filepath, $debug = false)
    {
        $reader = IOFactory::createReaderForFile($filepath);
        $sheets = $reader->listWorksheetNames($filepath);

        $callStartTime = microtime(true);
        $spreadsheet = $reader->load($filepath);
        $sheets = $reader->listWorksheetNames($filepath);
        foreach ($sheets as $sheet) {
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
            for ($row = 1; $row <= $highestRow; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    //for linux : $res = transliterate($val,array('diacritical_remove','cyrillic_transliterate','greek_transliterate'),'utf-8', 'utf-8');
                    //$res = transliterator_transliterate($val,array('diacritical_remove','cyrillic_transliterate','greek_transliterate'),'utf-8', 'utf-8');
                    //$res = UConverter:transcode($val, 'latin-1', '');
                    $res = URLify::transliterate($val);
                    $worksheet->setCellValue($cell->getColumn() . $row, $res);
                }
            }
        }
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($filepath);
        } catch (Exception $e) {
            error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
            $checks['errors'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
        }

        //$this->log($file['name'] . " : Transcode to latin in $callTime sec", 'info');

        return true;
    }

    /**
     * Check the file for the inclusion.
     * The following tests are performed:
     * - Check if the sheets in $data['Report']['sheets'] are present in the excel file
     * - Check the number of columns in each sheet against the MappingCol
     * Plus, the transcodification is done on the col set in MappingCol if the file is correct
     * @param $data - data from form (Report, Template)
     * @param $file - file to check
     * @param $filepath - filepath
     * @return mixed|array - list of errors or just empty array
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function checkSheetInclusionImport($data, $file, $filepath)
    {
        try {
            $reader = IOFactory::createReaderForFile($filepath);
        } catch (Exception $e) {
            $reader = false;
            error_log("Spreadsheet : cannot open file " . $filepath);
        }

        try {
            if (!$reader->canRead($filepath)) {
                $reader = false;
                error_log("Spreadsheet : cannot read file " . $filepath);
            }
        } catch (Exception $e) {
            $reader = false;
            error_log("Spreadsheet : cannot read file " . $filepath);
        }
        try {
            if (!$reader->listWorksheetInfo($filepath)) {
                $reader = false;
                error_log("Spreadsheet : cannot read file info  " . $filepath);
            }
        } catch (Exception $e) {
            $reader = false;
            error_log("Spreadsheet : cannot read file info " . $filepath);
        }

        //$this->remove_ghost_columns($filepath);

        $checks = $this->errorReceiver();
        if ($reader) {
            $MTable = TableRegistry::get('Damsv2.MappingTable');
            $MColumn = TableRegistry::get('Damsv2.MappingColumn');
            //$MTable = ClassRegistry::init('Damsv2.MappingTable');
            //$MColumn = ClassRegistry::init('Damsv2.MappingColumn');
            $sheets = $reader->listWorksheetNames($filepath);

            // Test if selected are present in the file
            $needed_sheets = $data['Report']['sheets'];

            if (!is_array($needed_sheets)) {
                $needed_sheets = [$needed_sheets];
            }
            $sheet_error = array_fill_keys(array_keys(array_flip($needed_sheets)), "OK");

            //case for sheet PD/LR
            if (!is_array($needed_sheets)) {
                //Expect 1 sheet for PD or LR
                if (count($sheets) > 1 && (in_array('PD', $sheets) || in_array('LR', $sheets))) {
                    $checks['errors']['missing_sheet'][] = "Only 1 sheet is needed in the file";
                    //$sheet_error[] = $needed_sheets;//ignored
                }
                $needed_sheets = [$needed_sheets];
            }

            // Counter guarantees
            if (array_search('A2', $needed_sheets)) {
                $table_sheets = $MTable->find('all', [
                    'fields'     => ['MappingTable.table_id', 'MappingTable.sheet_name'],
                    'conditions' => ['MappingTable.template_id' => $data['Template']['id']],
                    'recursive'  => -1
                ]);
                foreach ($table_sheets as $table) {
                    // check if A3 is present in this template
                    if ($table['MappingTable']['sheet_name'] == 'A3') {
                        if (!array_search('A3', $needed_sheets)) {
                            $checks['errors']['missing_sheet'][] = "<strong>A3</strong> is mandatory for counter guarantee portfolio when <strong>A2</strong> is selected";
                            $needed_sheets[] = "A3";
                            $sheet_error['A3'] = 'NOK';
                        }
                    }
                }
            }

            foreach ($needed_sheets as $sheet) {
                if (!in_array($sheet, $sheets)) {
                    $checks['errors']['missing_sheet'][] = "<strong>$sheet</strong> sheet isn't present in the File";
                    $sheet_error[$sheet] = 'NOK';
                }
            }

            // Test if more than present
            $spreadsheet = $reader->load($filepath);

            $col_to_transcode = [];
            foreach ($sheets as $key => $sheet) {

                if (in_array($sheet, $needed_sheets)) {

                    $worksheet = $spreadsheet->setActiveSheetIndex($key);
                    $table = $MTable->find('all', [
                                'fields'     => ['MappingTable.table_id'],
                                'conditions' => [
                                    'MappingTable.template_id' => $data['Template']['id'],
                                    'MappingTable.sheet_name'  => $sheet
                                ]
                            ])->first();

                    if (empty($table->table_id)) {
                        $checks['errors']['mapping_error'][] = "No <u>MappingTable</u> attached to the sheet <strong>$sheet</strong>";
                        if (in_array($sheet, $needed_sheets)) {
                            $sheet_error[$sheet] = 'NOK';
                        }
                    } else {
                        $highestColumm = $worksheet->getHighestColumn();
                        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumm);
                        $highestRow = $worksheet->getHighestRow(); // e.g. 10

                        $columns = $MColumn->find('all', [
                                    'fields'     => ['MappingColumn.excel_column', 'MappingColumn.column_id', 'MappingColumn.transcode', 'MappingColumn.datatype'],
                                    'conditions' => [
                                        'MappingColumn.table_id'       => $table->table_id,
                                        'MappingColumn.table_field !=' => 'action',
                                        'MappingColumn.excel_column >' => 0
                                    ],
                                    'order'      => ['MappingColumn.excel_column']
                                ])->all()->toArray();
                        //debug($columns);

                        if ($data['Report']['header'] == '1')
                            $row_start = 2;
                        else
                            $row_start = 1;
                        $empty_sheet = true; //assumption
                        $col_last = $highestColumnIndex;
                        while ($empty_sheet && ($col_last > 0)) {
                            for ($row = $row_start; $row <= $highestRow; ++$row) {
                                $cell = $worksheet->getCellByColumnAndRow($col_last, $row);
                                if ($cell->getValue() != '') {
                                    $empty_sheet = false;
                                }
                            }
                            // if for whole column there is no value
                            if ($empty_sheet) {
                                $col_last--;
                            }
                        }
                        if ($empty_sheet) {
                            // all ghost columns
                            $checks['errors']['no_data'][] = "There is no data in sheet <strong>$sheet</strong>";
                        }

                        $ghost_col = true;
                        while ($ghost_col) {
                            for ($lne = 0; $lne <= $highestRow; $lne++) {
                                $cell = $worksheet->getCellByColumnAndRow($highestColumnIndex, $lne);
                                $val = $cell->getValue();
                                if ($val != '') {
                                    $ghost_col = false;
                                }
                            }
                            if ($ghost_col) {
                                $worksheet->removeColumnByIndex($highestColumnIndex);
                                $highestColumnIndex--;
                            }
                        }

                        $delta = $highestColumnIndex - count($columns);
                        if ($delta != 0) {
                            $checks['errors']['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>" . $highestColumnIndex . "</strong> expected <strong>" . count($columns) . "</strong>)";
                            if (in_array($sheet, $needed_sheets)) {
                                $sheet_error[$sheet] = 'NOK';
                            }
                        } else {
                            if (in_array($sheet, $needed_sheets)) {
                                $sheet_error[$sheet] = 'OK';
                            }
                            //transcodification

                            foreach ($columns as $col) {
                                if ($col->transcode) {
                                    // Spreadsheet PHP begin col at 1
                                    $col_to_transcode[$sheet][$col['MappingColumn']['excel_column']] = true;
                                }
                            }
                            if (!empty($col_to_transcode[$sheet]) && $this->noError($checks['errors'])) {
                                $callStartTime = microtime(true);
                                if ($data['Report']['header'] == '1')
                                    $row = 2;
                                else
                                    $row = 1;
                                for ($row; $row <= $highestRow; ++$row) {
                                    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                                        if (!empty($col_to_transcode[$sheet][$col])) {
                                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                            $val = $cell->getValue();
                                            //$res = transliterate($val,array('diacritical_remove','cyrillic_transliterate','greek_transliterate'),'utf-8', 'utf-8');
                                            $res = utf8_encode(URLify::transliterate($val));
                                            //$worksheet->setCellValue($cell->getColumn().$row, $res);
                                            $res = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $res);
                                            $worksheet->setCellValueExplicit($cell->getColumn() . $row, $res, DataType::TYPE_STRING);
                                        }
                                    }
                                }
                                $callEndTime = microtime(true);
                                $callTime = $callEndTime - $callStartTime;
                                //$this->log("Report #" . $data['Report']['id'] . " : Transcode to latin in $callTime sec", 'info');
                            }

                            $this->csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet);
                        }
                    }
                }
            }

            $model_errorslog = TableRegistry::get('Damsv2.ErrorsLog');
            $error_data = $model_errorslog->ExcellError($data['Report']['id'], $sheet_error);

            if ($this->noError($checks['errors'])) {
                $info = pathinfo($filepath);
                $new_filepath = $info['dirname'] . DS . $info['filename'] . ".xlsx";
                $new_name = $info['filename'] . ".xlsx";

                try {
                    if (@unlink($filepath)) {
                        $writer = new Xlsx($spreadsheet);
                        $writer->save($new_filepath);
                        $checks['transcode'] = [
                            'filepath' => $new_filepath,
                            'filename' => $new_name
                        ];
                        $this->remove_ghost_columns($new_filepath);
                    } else {
                        $writer = new Xls($spreadsheet);
                        $writer->save($new_filepath);

                        $checks['transcode'] = [
                            'filepath' => $filepath,
                            'filename' => $new_name
                        ];
                        $this->remove_ghost_columns($filepath);
                    }
                } catch (Exception $e) {
                    error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
                    $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
                }
            }
        } else {
            $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> is not readable. Probably a corrupted Excel file";
        }
        return $checks;
    }

    /**
     * Check the file for the sampling.
     * The following tests are performed:
     * - Check if the sheets in $data['Sampling']['sheets'] are present in the excel file
     * - Check the number of columns in each sheet against the MappingCol
     * Plus, the transcodification is done on the col set in MappingCol if the file is correct
     * @param $data - data for check (Sampling, Template)
     * @param $file - file to check
     * @param $filepath - filepath
     * @return mixed|array - list of errors or just empty array
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function checkSheetsamplingUpdate($data, $file, $filepath)
    {
        $this->remove_ghost_columns($filepath);
        $reader = IOFactory::createReaderForFile($filepath);

        $checks = $this->errorReceiver();
        if ($reader) {
            $spreadsheet = $reader->load($filepath);
            $MTable = TableRegistry::get('Damsv2.MappingTable');
            $MColumn = TableRegistry::get('Damsv2.MappingColumn');
            $sheets = $reader->listWorksheetNames($filepath);

            // Test if selected are present in the file
            $needed_sheets = $data['Sampling']['sheets'];

            foreach ($needed_sheets as $sheet) {
                if (!in_array($sheet, $sheets))
                    $checks['errors']['missing_sheet'][] = "<strong>$sheet</strong> sheet isn't present in the File";
            }


            $col_to_transcode = [];
            foreach ($sheets as $key => $sheet) {

                if (in_array($sheet, $needed_sheets)) {
                    $table = $MTable->find('all', [
                                'fields'     => ['MappingTable.table_id'],
                                'conditions' => [
                                    'MappingTable.template_id' => $data['Template']['id'],
                                    'MappingTable.sheet_name'  => $sheet
                                ]
                            ])->first();
                    if (empty($table)) {
                        $checks['errors']['mapping_error'][] = "No <u>MappingTable</u> attached to the sheet <strong>$sheet</strong>";
                    } else {
                        $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
                        $highestColumm = $worksheet->getHighestColumn();

                        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumm);
                        $highestRow = $worksheet->getHighestRow();
                        $ghost_col = true;
                        for ($lne = 0; $lne <= $highestRow; $lne++) {
                            $cell = $worksheet->getCellByColumnAndRow($highestColumnIndex, $lne);
                            $val = $cell->getValue();
                            if ($val != '') {
                                $ghost_col = false;
                            }
                        }
                        if ($ghost_col) {
                            $worksheet->removeColumnByIndex($highestColumnIndex);
                            $highestColumnIndex--;
                        }
                        /* 	$cell = $worksheet->getCellByColumnAndRow($highestColumnIndex, 1);
                          $val = $cell->getValue();
                          while (($val == "") && ($highestColumm > 1))
                          {

                          //$worksheet->removeColumnByIndex($highestColumnIndex);
                          $highestColumm--;
                          $cell = $worksheet->getCellByColumnAndRow($highestColumnIndex, 1);
                          $val = $cell->getValue();
                          //$checks['errors']['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>".$highestColumnIndex."</strong> expected <strong>".$count_columns."</strong>)";
                          } */

                        $columns = $MColumn->find('all', [
                                    'fields'     => ['MappingColumn.excel_column', 'MappingColumn.column_id', 'MappingColumn.transcode'],
                                    'conditions' => [
                                        'MappingColumn.table_id'            => $table->table_id,
                                        'MappingColumn.excel_column >'      => 0,
                                        'MappingColumn.table_field NOT IN ' => ['action', 'error_message'],
                                    ],
                                    'order'      => ['MappingColumn.excel_column']
                                ])->toArray();

                        // manual sampling : $sheet MS
                        // transaction sampling : $sheet TRS
                        // sample upload : $sheet SU
                        $correction = 0;
                        //debug($data);
                        if (!empty($data['correction']) && (strcmp($data['correction'], '1') === 0)) {
                            // fields action and error_message added
                            $correction += 2;
                        }
                        $count_columns = (count($columns) + $correction);
                        $delta = $highestColumnIndex - $count_columns;
                        if (($delta > 0.001) || ($delta < -0.001)) {
                            $checks['errors']['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>" . $highestColumnIndex . "</strong> expected <strong>" . $count_columns . "</strong>)";
                        } else {
                            //transcodification
                            $highestRow = $worksheet->getHighestRow(); // e.g. 10

                            foreach ($columns as $col) {
                                if ($col['MappingColumn']['transcode']) {
                                    $col_to_transcode[$sheet][$col['MappingColumn']['excel_column']] = true;
                                }
                            }
                            $this->csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet);
                        }
                    }
                }
            }

            if ($this->noError($checks['errors'])) {
                $info = pathinfo($filepath);
                $new_filepath = $info['dirname'] . DS . $info['filename'] . ".xlsx";
                $new_name = $info['filename'] . ".xlsx";

                try {
                    if (@unlink($filepath)) {
                        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
                        $writer->save($new_filepath);
                        $checks['transcode'] = [
                            'filepath' => $new_filepath,
                            'filename' => $new_name
                        ];
                        $this->remove_ghost_columns($new_filepath);
                    } else {
                        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
                        $writer->save($filepath);
                        $this->remove_ghost_columns($filepath);
                    }
                } catch (Exception $e) {
                    error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
                    $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
                }
            }
        } else {
            $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> is not readable. Probably a corrupted Excel file";
        }
        return $checks;
    }

    /**
     * set the dates of format DD/MM/YYYY in date type cell
     * @param $filepath - filepath
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function fixDates($filepath, $format_out = 'Xlsx')
    {
        $reader = IOFactory::createReaderForFile($filepath);
        $sheets = $reader->listWorksheetNames($filepath);
        $spreadsheet = $reader->load($filepath);
        foreach ($sheets as $key => $sheet) {
            $worksheet = $spreadsheet->setActiveSheetIndex($key);
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();

                foreach ($cellIterator as $cell) {
                    $val = $cell->getValue();
                    $date = [];
                    if (preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $val, $date) === 1) {
                        $date = explode("/", $date[0]);
                        $time = Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
                        $cell->setValue($time);
                        error_log("fixdate setting date");
                        $worksheet->getStyle($cell->getCoordinate())
                                ->getNumberFormat()
                                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
                    }
                }
            }
        }
        $writer = IOFactory::createWriter($spreadsheet, $format_out);
        $writer->save($filepath);
    }

    /**
     * create xlsx file if input is xls
     * @param $filename - filename
     * @param $filepath - filepath
     * @return array - info from new file
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function createXlsxfile($filename, $filepath)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $checks = $this->errorReceiver();

        $reader = IOFactory::createReaderForFile($filepath);

        $spreadsheet = $reader->load($filepath);
        $sheets = $reader->listWorksheetNames($filepath);

        $info = pathinfo($filepath);
        $new_filepath = $info['dirname'] . DS . $info['filename'] . ".xlsx";
        try {
            if (@unlink($filepath)) {
                $writer = new Xlsx($spreadsheet);
                $writer->save($new_filepath);
                $this->remove_ghost_columns($new_filepath);
            }
        } catch (Exception $e) {
            error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
            $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
        }

        return ['name' => $info['filename'] . ".xlsx", 'path' => $new_filepath, 'errors' => $checks['errors']];
    }

    /**
     * Check the file for editing business key
     * Number of columns and sheet name
     * @param $data - Report from form
     * @param $file - file to check
     * @param $filepath - filepath
     * @return array - list of errors
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function checkSheetBulkBK($data, $file, $filepath)
    {
        $reader = IOFactory::createReaderForFile($filepath);

        $errors = $this->errorReceiver();
        if ($reader) {
            $sheets = $reader->listWorksheetNames($filepath);

            // Test if selected are present (A1, A2, B are mandatory)
            $needed_sheet = $data['Import']['sheet'];
            if (!in_array($needed_sheet, $sheets)) {
                $errors['errors']['missing_sheet'][] = "<strong>$needed_sheet</strong> sheet isn't present in the File";
            }

            // Test if more than present
            $MTable = TableRegistry::get('Damsv2.MappingTable');
            $MColumn = TableRegistry::get('Damsv2.MappingColumn');
            $spreadsheet = $reader->load($filepath);
            foreach ($sheets as $key => $sheet) {
                if ($sheet == $needed_sheet) {
                    $worksheet = $spreadsheet->setActiveSheetIndex($key);
                    $highestColumm = $worksheet->getHighestColumn();
                    $highestColumnIndex = Coordinate::columnIndexFromString($highestColumm);
                    $highestRow = $this->getHighestRow($worksheet);
                    //$highestRow = $worksheet->getHighestRow();
                    error_log("edit BK row count: " . $highestRow);
                    if ($highestRow < 2) {
                        $errors['errors']['no_data'][] = "No data in " . $sheet;
                        return $errors;
                    }
                    switch ($needed_sheet) {
                        case 'A1':
                            $nbColumns = 4;
                            break;
                        case 'A2':
                        case 'A3':
                            $nbColumns = 6;
                            break;
                        case 'B':
                            $nbColumns = 6;
                            break;
                        default:
                            $nbColumns = 0;
                            break;
                    }

                    $delta = $highestColumnIndex - $nbColumns;
                    if ($delta != 0) {
                        $errors['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>" . $highestColumnIndex . "</strong> expected <strong>" . $nbColumns . "</strong>)";
                    } else {
						for($col = 1; ($col <= $highestColumnIndex); $col++)
						{
							for ($row_tmp = 1 ; $row_tmp <= $highestRow; $row_tmp++ )
							{
								$val = $worksheet->getCellByColumnAndRow($col, $row_tmp)->getValue();
								$val = URLify::transliterate($val);
								$worksheet->setCellValueByColumnAndRow($col, $row_tmp, $val);
							}
						}
						$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
						$writer->save($filepath);
						//$this->remove_ghost_columns($filepath);
                        $this->csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet);
                    }
                }
            }
            // $this->File->keepLastNFiles(WWW_WIN.'/data/damsv2/bulk/in/', 'xlsx', 10);
            // $this->File->keepLastNFiles(WWW_WIN.'/data/damsv2/bulk/in/', 'xls', 10);
        } else {
            $errors['other'][] = "The file <strong>" . basename($filepath) . "</strong> is not readable. Probably a corrupted Excel file";
        }
        return $errors;
    }

    /**
     * Check file for data editing
     * Check sheet name number of columns
     * @param $data
     * @param $file
     * @param $filepath
     * @return array
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    function checkSheetBulkData($data, $file, $filepath)
    {
        $reader = IOFactory::createReaderForFile($filepath);

        $errors = [];
        if ($reader) {
            $sheets = $reader->listWorksheetNames($filepath);

            // Test if selected are present (A1, A2, B are mandatory)
            $needed_sheet = $data['Import']['sheet'];

            if (!in_array($needed_sheet, $sheets))
                $errors['errors']['missing_sheet'][] = "<strong>$needed_sheet</strong> sheet isn't present in the File";

            // Test if more than present
            $MTable = TableRegistry::get('Damsv2.MappingTable');
            $MColumn = TableRegistry::get('Damsv2.MappingColumn');
            $spreadsheet = $reader->load($filepath);

            foreach ($sheets as $key => $sheet) {

                if ($sheet == $needed_sheet) {
                    $table = $MTable->find('all', [
                                'fields'     => ['MappingTable.table_id'],
                                'conditions' => [
                                    'MappingTable.template_id' => $data['Template']['id'],
                                    'MappingTable.sheet_name'  => $sheet
                                ]
                            ])->first();

                    if (empty($table)) {
                        $errors['mapping_error'][] = "No <u>MappingTable</u> attached to the sheet <strong>$sheet</strong>";
                    } else {
                        $worksheet = $spreadsheet->setActiveSheetIndex($key);
                        $highestColumm = $worksheet->getHighestColumn();
                        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumm);
                        $highestRow = $worksheet->getHighestRow();

//                        $empty_sheet = true;
//                        for ($row = 2; $row <= $highestRow; ++$row) {
//                            error_log("row=" . $row);
//                            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
//                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
//                                $val = $cell->getValue();
//                                if ($val != '') {
//                                    $empty_sheet = false;
//                                    error_log("edit data sheeet " . $sheet . " " . $col . " " . $row . " : data found " . $val);
//                                } else {
//                                    error_log("edit data sheeet " . $sheet . " " . $col . " " . $row . " :  NO data found (" . $empty_sheet . ") '" . $val . "'");
//                                }
//                            }
//                        }
//                        if ($empty_sheet) {
//                            $errors['no_data'][] = "There is no data in sheet <strong>$sheet</strong>";
//                        }

                        $empty_sheet = true;
                        for ($row = 2; $row <= $highestRow; ++$row) {
                            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                $val = $cell->getValue();
                                if ($val != '') {
                                    $empty_sheet = false;
                                }
                            }
                        }
                        if ($empty_sheet) {
                            $errors['no_data'][] = "There is no data in sheet <strong>$sheet</strong>";
                        }
                        $ghost_col = true;
                        while ($ghost_col) {
                            for ($row = 2; $row <= $highestRow; ++$row) {
                                for ($lne = 0; $lne <= $highestRow; $lne++) {
                                    $cell = $worksheet->getCellByColumnAndRow($highestColumnIndex, $lne);
                                    $val = $cell->getValue();
                                    if ($val != '') {
                                        $ghost_col = false;
                                    }
                                }
                            }
                            if ($ghost_col) {
                                $worksheet->removeColumnByIndex($highestColumnIndex);
                                $highestColumnIndex--;
                            }
                        }

                        //transcodification on relevant columns
                        $columns_transcode = $MColumn->find('all', [
                            'fields'     => ['MappingColumn.column_id', 'MappingColumn.excel_column'],
                            'conditions' => [
                                'MappingColumn.table_id'       => $table->table_id,
                                'MappingColumn.table_field !=' => 'action',
                                'MappingColumn.transcode >'    => 0
                            ],
                            'order'      => ['MappingColumn.excel_column']
                        ]);
                        $save_transcode = false;

                        foreach ($columns_transcode as $column_transcode) {
                            $save_transcode = true;
                            $col = $column_transcode['MappingColumn']['excel_column'];
                            for ($row_tmp = 2; $row_tmp <= $highestRow; $row_tmp++) {
                                $val = $worksheet->getCellByColumnAndRow($col, $row_tmp)->getValue();
                                error_log("edit template id  val spreadsheet: " . json_encode($val));
                                if (!empty($val)) {
                                    $val = URLify::transliterate($val);
                                    $worksheet->setCellValueByColumnAndRow($col, $row_tmp, $val);
                                }
                            }
                        }
                        if ($save_transcode) {
                            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                            $writer->save($filepath);
                            $this->remove_ghost_columns($filepath);
                        }

                        $columns = $MColumn->find('all', [
                                    'fields'     => ['MappingColumn.column_id'],
                                    'conditions' => [
                                        'MappingColumn.table_id'            => $table->table_id,
                                        'MappingColumn.table_field NOT IN ' => ['action', 'error_message'],
                                        'MappingColumn.excel_column >'      => 0
                                    ],
                                    'order'      => ['MappingColumn.excel_column']
                                ])->toArray();
//debug($columns);
                        //only for 'expired to performing' in correction mode
                        if (!empty($data['Import']['correction']) && ($data['Import']['sheet'] == 'EP')) {
                            if (($highestColumnIndex != 2) && ($highestColumnIndex != 3)) {
                                $errors['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>" . $highestColumnIndex . "</strong> expected <strong>2</strong> or <strong>3</strong>)";
                            }
                        } elseif (empty($data['Import']['correction'])) {
                            $delta = $highestColumnIndex - count($columns);
                            if ($delta != 0) {
                                $errors['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>" . $highestColumnIndex . "</strong> expected <strong>" . count($columns) . "</strong>)";
                            }
                            $this->csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet);
                        } else {
                            $this->csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet);
                        }
                    }
                }
            }
            // $this->File->keepLastNFiles(WWW.'/data/damsv2/bulk/in/', 'xlsx', 10);
            // $this->File->keepLastNFiles(WWW.'/data/damsv2/bulk/in/', 'xls', 10);
        } else {
            $errors[] = "The file <strong>" . basename($filepath) . "</strong> is not readable. Probably a corrupted Excel file";
        }
        return $errors;
    }

    function checkSheetSplitData($data, $file, $filepath)
    {
        $reader = IOFactory::createReaderForFile($filepath);

        $errors = array();
        if ($reader) {
            $sheets = $reader->listWorksheetNames($filepath);

            // Test if selected are present (A1, A2, B are mandatory)
            //$needed_sheet = $data['Import']['sheet'];
            $needed_sheet = array('a1_sp_old', 'a1_sp_new');

            /* if(!in_array($needed_sheet, $sheets))
              $errors['errors']['missing_sheet'][] = "<strong>$needed_sheet</strong> sheet isn't present in the File";
             */
            // Test if more than present
            $MTable = ClassRegistry::init('Damsv2.MappingTable');
            $MColumn = ClassRegistry::init('Damsv2.MappingColumn');
            $spreadsheet = $reader->load($filepath);

            foreach ($sheets as $key => $sheet) {
                if ($sheet == $needed_sheet) {

                    $worksheet = $spreadsheet->setActiveSheetIndex($key);
                    $highestColumm = $worksheet->getHighestColumn();
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumm);
                    $highestRow = $worksheet->getHighestRow();
                }
            }
            // $this->File->keepLastNFiles(WWW.'/data/damsv2/bulk/in/', 'xlsx', 10);
            // $this->File->keepLastNFiles(WWW.'/data/damsv2/bulk/in/', 'xls', 10);
        } else {
            $errors[] = "The file <strong>" . basename($filepath) . "</strong> is not readable. Probably a corrupted Excel file";
        }
        return $errors;
    }

    /**
     * Test a generation of Excel file using PHPExcel vendor
     * Dev use only
     * @param array $sheet
     * @param array $skeleton
     * @param $filepath
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Writer_Exception
     */
    function generateExcel($sheet = [], $skeleton = [], $filepath)
    {
        if (sizeof($sheet) > 0) {
            $spreadsheet = new Spreadsheet();

            $spreadsheet->getProperties()
                    ->setCreator("EIF DAMS TEAM")
                    ->setTitle("XLSX Document")
                    ->setCategory("Dams/Treasury");

            $worksheet = $spreadsheet->setActiveSheetIndex(0);
            $objects = [];
            foreach ($sheet as $models) {
                foreach ($models as $key => $model) {
                    error_log('key : ' . $key . ' in skeleton: ' . json_encode($skeleton));
                    if (in_array($key, $skeleton)) {
                        $objects[] = $model;
                    }
                    unset($model);
                }
                unset($models);
            }
            $row = 0;
            $rows_count = count($objects);
            while ($row < $rows_count) {
                $columns = array_shift($objects);

                $i = 1;
                $j = 1;
                foreach ($columns as $column => $data) {
                    if (($row) == 0) {
                        $worksheet->setCellValueByColumnAndRow($j++, $row + 1, $column);
                    }
//                    if ($this->keyContains($column, ['transaction_reference', 'fiscal_number'])) {
                    $worksheet->getCellByColumnAndRow($i++, $row + 2)->setValueExplicit($data, DataType::TYPE_STRING);
//                    } else {
//                        $worksheet->setCellValueByColumnAndRow($i++, $row + 2, $data);
//                    }
                }
                $row++;
                unset($columns);
            }
            unset($objects);
            try {
                $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
                $writer->save($filepath);
                $this->remove_ghost_columns($filepath);
            } catch (Exception $e) {
                error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
                $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
            }

            $this->File->keepLastNFiles('/var/www/html/data/damsv2/export/', 'xlsx', 10);
        }
    }

    public function generateCsvFromQuery($result = [], $filepath, $is_orm = true)
    {
        if (sizeof($result) > 0) {
            $headers = $is_orm ? array_keys($result[0]->toArray()) : array_keys($result[0]);
            $lines = $result;
            if ($is_orm) {
                $lines = array_map(function ($value) {
                    $temp = $value->toArray();
                    if (isset($temp['portfolio'])) {
                        $temp['portfolio'] = !empty($value->portfolio->owner) ? $value->portfolio->v_user->first_name . ' ' . $value->portfolio->v_user->last_name : $value->portfolio->portfolio_name;
                    }
                    return $temp;
                }, $result);
            }
            //write data to spreadsheet
            ob_start();
            $df = fopen($filepath, 'w');
            fputcsv($df, $headers, ';');
            foreach ($lines as $value) {
                foreach ($value as $key=>$val) {
                    if (is_a($val, 'DateTime')) {
                        $value[$key] = $val->format('d/m/Y');
                    }
                }
                fputcsv($df, $value, ';');
            }
            fclose($df);
            return ob_get_clean();
        }
        return false;
    }

    public function generateExcelFromQuery($result = [], $skeleton = [], $filepath, $is_orm = true)
    {
        return $this->generateExcelFromQueryHeaderMapping($result, $skeleton, $filepath, $is_orm, function($a) { return $a;});
    }

    public function generateExcelFromQueryDefaultHeaderMapping($result = [], $skeleton = [], $filepath, $is_orm = true) {
        return $this->generateExcelFromQueryHeaderMapping($result, $skeleton, $filepath, $is_orm, function($a) { return str_replace('_', ' ', $a);});
    }
    
    public function generateExcelFromQueryHeaderMapping($result = [], $skeleton = [], $filepath, $is_orm = true, $mapping)
    {
        if (sizeof($result) > 0) {
            //spreadsheet headers
            $headers = $is_orm ? array_keys($result[0]->toArray()) : array_keys($result[0]);
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                    ->setCreator("EIF DAMS TEAM")
                    ->setTitle("XLSX Document")
                    ->setCategory("Dams/Treasury");
            $spreadsheet->setActiveSheetIndex(0);
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet->setTitle($skeleton[0]);

            //write data to spreadsheet
            $col = 1;
            foreach ($headers as $title) {
                $row = 2;
                //header
                $header_title = $mapping($title);
                $worksheet->setCellValueByColumnAndRow($col, 1, $header_title);
                foreach ($result as $value) {
                    //data
                    if ($title === 'portfolio') {
                        $data_value = !empty($value->portfolio->owner) ? $value->portfolio->v_user->first_name . ' ' . $value->portfolio->v_user->last_name : $value->portfolio->portfolio_name;
                    } else {
                        $data_value = $value[$title];
                    }

                    $worksheet->setCellValueByColumnAndRow($col, $row, $data_value);
                    $row++;
                }
                $col++;
            }
            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($filepath);
                $this->remove_ghost_columns($filepath);
            } catch (Exception $e) {
                error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
                $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
            }
            return true;
        } else {
            return false;
        }
    }

    function keyContains($key, $contains)
    {
        foreach ($contains as $contain) {
            if (strpos(strtolower($key), strtolower($contain)) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check the file for the inclusion correction.
     * The following tests are performed:
     * - Check if the sheets in $data['Report']['sheets'] are present in the excel file
     * - Check the number of columns in each sheet against the MappingCol
     * Plus, the transcodification is done on the col set in MappingCol if the file is correct
     * @param $data - data from form (Report, Template)
     * @param $file - file to check
     * @param $filepath - filepath
     * @return mixed|array - name, path, list of errors
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function checkSheetInclusionImportCorrection($report, $file, $filepath)
    {

        $info = pathinfo($filepath);
        $new_filepath = $info['dirname'] . DS . $info['filename'] . ".xlsx";
        copy($filepath, '/tmp/' . $info['filename'] . time() . '.xlsx');
        $reader = IOFactory::createReaderForFile($filepath);
        $checks = $this->errorReceiver();

        if ($reader) {
            $spreadsheet = $reader->load($filepath);
            $MTable = TableRegistry::get('Damsv2.MappingTable');
            $MColumn = TableRegistry::get('Damsv2.MappingColumn');
            $sheets = $reader->listWorksheetNames($filepath);

            // Test if selected are present in the file
            $needed_sheets = explode('$$', $report->sheets);
            //case for sheet PD/LR
            if (!is_array($needed_sheets)) {
                //Expect 1 sheet for PD or LR
                if (count($sheets) > 1 && (in_array('PD', $sheets) || in_array('LR', $sheets))) {
                    $checks['errors']['missing_sheet'][] = "Only 1 sheet is needed in the file";
                }
                $needed_sheets = [$needed_sheets];
            }

            // Counter guarantees
            if (array_search('A2', $needed_sheets)) {
                $table_sheets = $MTable->find('all', [
                    'fields'     => ['MappingTable.table_id', 'MappingTable.sheet_name'],
                    'conditions' => ['MappingTable.template_id' => $report->template_id],
                    'recursive'  => -1
                ]);
                foreach ($table_sheets as $table) {
                    // check if A3 is present in this template
                    if ($table['MappingTable']['sheet_name'] == 'A3') {
                        if (!array_search('A3', $needed_sheets)) {
                            $checks['errors']['missing_sheet'][] = "<strong>A3</strong> is mandatory for counter guarantee portfolio when <strong>A2</strong> is selected";
                            $needed_sheets[] = "A3";
                        }
                    }
                }
            }
            $sheet_error = [];
            foreach ($needed_sheets as $sh) {
                $sheet_error[$sh] = 'OK';
            }


            foreach ($needed_sheets as $sheet) {
                if (!in_array($sheet, $sheets)) {
                    $checks['errors']['missing_sheet'][] = "<strong>$sheet</strong> sheet isn't present in the File";
                    $sheet_error[$sheet] = 'NOK';
                }
            }

            // Test if more than present
            //$spreadsheet = $reader->load($filepath);

            $col_to_transcode = [];
            foreach ($sheets as $key => $sheet) {
                if (in_array($sheet, $needed_sheets)) {
                    $table = $MTable->find('all', [
                                'fields'     => ['MappingTable.table_id'],
                                'conditions' => [
                                    'MappingTable.template_id' => $report->template_id,
                                    'MappingTable.sheet_name'  => $sheet
                                ]
                            ])->first();
                    if (empty($table)) {
                        $checks['errors']['mapping_error'][] = "No <u>MappingTable</u> attached to the sheet <strong>$sheet</strong>";
                        $sheet_error[$sheet] = 'NOK';
                    } else {
                        $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
                        $data = $spreadsheet->getActiveSheet()->toArray();
                        $highestColumm = $worksheet->getHighestColumn();
                        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumm);
                        $highestRow = $worksheet->getHighestRow();

                        $empty_sheet = true;
                        for ($row = 2; $row <= $highestRow; ++$row) {//we always have the headers in correction mode
                            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                                $val = $worksheet->getCellByColumnAndRow($col, $row);

                                if ($empty_sheet && ($val != '')) {
                                    $empty_sheet = false;
                                }
                            }
                        }
                        if ($empty_sheet) {
                            $checks['errors']['no_data'][] = "There is no data in sheet <strong>$sheet</strong>";
                        }

                        //check empty cols (shadow column)
                        $shadow_columns = [];
                        for ($col = 1; $col <= $highestColumnIndex; $col++) {
                            $shadow_columns[$col] = true;
                            $val = $worksheet->getCellByColumnAndRow($col, 1);
                            if ($val != '') {
                                $shadow_columns[$col] = false;
                            }
                            if ($report->header == '1')
                                $row = 2;
                            else
                                $row = 1;
                            for ($row; (($row <= $highestRow) && ($shadow_columns[$col])); $row++) {
                                $val = $worksheet->getCellByColumnAndRow($col, $row);
                                if ($val != '') {
                                    $shadow_columns[$col] = false;
                                }
                            }
                            if ($shadow_columns[$col]) {
                                $worksheet->removeColumnByIndex($col); //remove the shadow column
                                $highestColumnIndex--;
                                //$checks['errors'][] = "Shadow columns in sheet ".$sheet." at index ".$col;
                            }
                        }

                        $columns = $MColumn->find('all', [
                                    'fields'     => ['MappingColumn.excel_column', 'MappingColumn.column_id', 'MappingColumn.transcode'],
                                    'conditions' => [
                                        'MappingColumn.table_id'            => $table->table_id,
                                        'MappingColumn.table_field NOT IN ' => ['action', 'error_message'],
                                        'MappingColumn.excel_column >'      => 0
                                    ],
                                    'order'      => ['MappingColumn.excel_column']
                                ])->toArray();
                        $count_columns = (count($columns) + 2);
                        $delta = $highestColumnIndex - $count_columns;

                        if ($delta != 0) {
                            $checks['errors']['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>" . $highestColumnIndex . "</strong> expected <strong>" . $count_columns . "</strong>)";
                            if (in_array($sheet, $needed_sheets)) {
                                $sheet_error[$sheet] = 'NOK';
                            }
                        } else {
                            //transcodification
                            $highestRow = $worksheet->getHighestRow(); // e.g. 10

                            foreach ($columns as $col) {
                                if ($col['MappingColumn']['transcode']) {
                                    $col_to_transcode[$sheet][$col['MappingColumn']['excel_column']] = true;
                                }
                            }
                            if (!empty($col_to_transcode[$sheet]) && $this->no_error($checks['errors'])) {
                                $callStartTime = microtime(true);
                                if ($report->header == '1')
                                    $row = 2;
                                else
                                    $row = 1;
                                for ($row; $row <= $highestRow; ++$row) {
                                    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                                        if (!empty($col_to_transcode[$sheet][$col])) {
                                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                            $val = $cell->getValue();
                                            //$res = transliterate($val,array('diacritical_remove','cyrillic_transliterate','greek_transliterate'),'utf-8', 'utf-8');
                                            $res = utf8_encode(URLify::transliterate($val));
                                            $res = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $res);
                                            //$worksheet->setCellValue($cell->getColumn().$row, $res);
                                            //aplply correction in the case where scientic values
                                            if (preg_match('/^[0-9]+[.,][0-9]+[E]\+[0-9]+/', $cell->getValue())) {
                                                $worksheet->setCellValueExplicit($cell->getColumn() . $row, $res, DataType::TYPE_STRING);
                                            }
                                        }
                                    }
                                }
                                $callEndTime = microtime(true);
                                $callTime = $callEndTime - $callStartTime;
                                $this->log("Report #" . $report->report_id . " : Transcode to latin in $callTime sec", 'info');
                            }

                            if (!$this->noError($checks['errors'])) {//check not all rows ignored https://eifsas.atlassian.net/browse/DAMS-762
                                /* $empty_column = array();
                                  foreach ($columns as $col)
                                  {
                                  if($report->header == '1') $row = 2; else $row = 1;
                                  $i = $col['MappingColumn']['excel_column']-1;
                                  $empty_column[$i] = true;
                                  for ($row; (($row <= $highestRow) && ($empty_column[$i])); ++ $row)
                                  {
                                  $cell = $worksheet->getCellByColumnAndRow($i, $row);
                                  $val = $cell->getValue();
                                  if (!empty($val))
                                  {
                                  $empty_column[$i] = false;
                                  }
                                  }
                                  } */
                                //Exception fo 'action' column
                                $action_column = null;
                                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                                    $cell = $worksheet->getCellByColumnAndRow($col, 1);
                                    if ($cell->getValue() == 'action') {
                                        $action_column = $col;
                                    }
                                }
                                if (!empty($action_column)) {// if we have an 'action' column
                                    $all_ignore = true;
                                    if ($report->header == '1')
                                        $row = 2;
                                    else
                                        $row = 1;
                                    for ($row; $row <= $highestRow; ++$row) {
                                        $cell = $worksheet->getCellByColumnAndRow($action_column, $row);
                                        if ($cell->getValue() != 'I') {
                                            $all_ignore = false;
                                        }
                                    }
                                    if ($all_ignore) {
                                        $checks['errors']['no_data'][] = "there is no data in sheet <strong>$sheet</strong> (all ignored)";
                                    }
                                }
                            }

                            if (in_array($sheet, $needed_sheets)) {
                                $sheet_error[$sheet] = 'OK';
                            }
                            $this->csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet);
                        }
                    }
                }
            }
            $model_errorslog = TableRegistry::get('Damsv2.ErrorsLog');
            $error_data = $model_errorslog->ExcellError($report->report_id, $sheet_error);

            if ($this->noError($checks['errors'])) {
                $info = pathinfo($filepath);
                $new_filepath = $info['dirname'] . DS . $info['filename'] . ".xlsx";
                $new_name = $info['filename'] . ".xlsx";

                try {
                    if (@unlink($filepath)) {
                        $writer = new Xlsx($spreadsheet);
                        $writer->save($new_filepath);
                        $checks['transcode'] = [
                            'filepath' => $new_filepath,
                            'filename' => $new_name
                        ];
                        //$this->remove_ghost_columns($new_filepath);
                    } else {
                        $writer = new Xls($spreadsheet);
                        $writer->save($filepath);
                        //$this->remove_ghost_columns($filepath);
                    }
                } catch (Exception $e) {
                    error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
                    $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
                }
            } else {
                error_log("unknown error : " . json_encode($checks['errors']));
            }
        } else {
            $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> is not readable. Probably a corrupted Excel file";
        }

        return ['name' => $info['filename'] . ".xlsx", 'path' => $new_filepath, 'errors' => $checks['errors']];
    }

    /*
      to avoid some dates issues when SAS import an excel file, we export to CSV
     */

    public function csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet)
    {
        //csv file for this sheet
        $info = pathinfo($filepath);
        $file_name_csv = explode('.', $info['filename']);
        if (empty($this->sheet_table[$sheet])) {
            $this->sheet_table[$sheet] = 'TABLE';
        }

        $csv_file_path = "/var/www/html/data/damsv2/export" . DS . $file_name_csv[0] . "_" . $this->sheet_table[$sheet] . ".csv";
        $fp = fopen($csv_file_path, 'w');

        for ($row_csv = 1; $row_csv <= $highestRow; $row_csv++) {
            $csv_line = [];
            $empty_line = true;
            for ($col_csv = 1; $col_csv <= $highestColumnIndex; $col_csv++) {
                $cell = $worksheet->getCellByColumnAndRow($col_csv, $row_csv);
                $val = $cell->getValue();
                if (!empty($val)) {
                    $empty_line = false;
                    $val = preg_replace("/\\r\\n|\\r|\\n/", '', $val);

                    $excel_timestamp = preg_match('/^[0-9]+$/', $val); //make sure the date is a valid one, otherwise php replaces it with current date
                    if ((Date::isDateTime($cell)) && ($excel_timestamp == 1)) {
                        $val = date("d-m-Y", Date::excelToTimestamp($val));
                    }
                }
                $csv_line[] = $val;
            }
            if (!$empty_line) {
                fputcsv($fp, $csv_line, ';');
            }
        }

        fclose($fp);
    }

    /*
      to avoid some dates issues when SAS import an excel file, we export to CSV
     */

    public function csvExportBr($filepath)
    {
        $info = pathinfo($filepath);
        $file_name_csv = explode('.', $info['filename']);

        $csv_file_path = "/var/www/html/data/damsv2/export" . DS . $file_name_csv[0] . ".csv";

        $reader = IOFactory::createReaderForFile($filepath);
        $sheets = $reader->listWorksheetNames($filepath);

        $spreadsheet = $reader->load($filepath);
        $writer = IOFactory::createWriter($spreadsheet, "Csv");
        $writer->save($csv_file_path);
        return $csv_file_path;
    }

    public function analyseSasResultofNumCheck($report_id, $version)
    {
        $errors = [];
        $filepath = '/var/www/html/data/damsv2/num_date_excel/final_error_' . $report_id . '_v' . $version . '.xlsx';
        if (file_exists($filepath)) {
            $reader = IOFactory::createReaderForFile($filepath);
            if ($reader) {
                $spreadsheet = $reader->load($filepath);
                $worksheet = $spreadsheet->setActiveSheetIndex(0);
                $highestRow = $worksheet->getHighestRow();
                $row = 2; // start at 1 + header
                for ($row; $row <= $highestRow; ++$row) {
                    $cell = $worksheet->getCellByColumnAndRow(3, $row);
                    $val = $cell->getValue();
                    error_log("num check : " . $val);
                    if ($val != '') {
                        $val = str_replace('.', ',', $val);
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $sheet_error = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $row_error = $cell->getValue();
                        $errors['other'][] = "numerical format error in <b>" . $sheet_error . "</b> sheet, row <b>" . $row_error . "</b>, column(s) <b>" . $val . "</b>";

                        error_log("num check : numerical format error in sheet " . $sheet_error . " at row " . $row_error . " column " . $val);
                    }
                    $cell = $worksheet->getCellByColumnAndRow(4, $row);
                    $val = $cell->getValue();

                    error_log("num check date: " . $val);
                    if ($val != '') {
                        $val = str_replace('.', ',', $val);
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $sheet_error = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $row_error = $cell->getValue();
                        $errors['other'][] = "date format error in <b>" . $sheet_error . "</b> sheet, row <b>" . $row_error . "</b>, column(s) <b>" . $val . "</b>";

                        error_log("num check : date format error in sheet " . $sheet_error . " at row " . $row_error . " column " . $val);
                    }
                }
            }
        } else {
            $errors[] = "The SAS check on dates and numerical fields could not be performed, Please contact the SAS Support team";
        }
        error_log("num check : " . json_encode($errors));
        return $errors;
    }

    public function checkFormat($filepath, $format)
    {
        $detect = [];
        $reader = IOFactory::createReaderForFile($filepath);
        $sheets = $reader->listWorksheetNames($filepath);
        $spreadsheet = $reader->load($filepath);
        foreach ($sheets as $sheet) {
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            for ($row = 2; $row <= $highestRow; ++$row) {
                for ($col = 1; $col < $highestColumnIndex; ++$col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();

                    $column = $col - 1;
                    if (empty($format[$column])) {
                        $detect[] = "too many columns : " . $cell->getColumn();
                        return $detect; // stop here and return
                    }
                    if ((preg_match($format[$column], $val) !== 1)) {// does not fit format
                        $detect[] = $row . ":" . $col . " : got " . $val . " : expected " . $format[$column];
                    }
                }
            }
        }
        return $detect;
    }

    public function checkSheetSmeRatingUpdate($data, $file, $filepath)
    {
        $reader = IOFactory::createReaderForFile($filepath);
        $checks = $this->errorReceiver();
        if ($reader) {
            $spreadsheet = $reader->load($filepath);
            $MTable = TableRegistry::get('Damsv2.MappingTable');
            $MColumn = TableRegistry::get('Damsv2.MappingColumn');
            $sheets = $reader->listWorksheetNames($filepath);

            // Test if selected are present in the file
            //$needed_sheets = $data['SmeRating']['sheets'];
            $needed_sheets = ['mapping'];

            foreach ($needed_sheets as $sheet) {
                if (!in_array($sheet, $sheets))
                    $checks['errors']['missing_sheet'][] = "The sheet named 'mapping' is missing.";
            }


            $col_to_transcode = [];
            foreach ($sheets as $key => $sheet) {

                if (in_array($sheet, $needed_sheets)) {
                    $table = $MTable->find('all', [
                                'fields'     => ['MappingTable.table_id'],
                                'conditions' => [
                                    'MappingTable.template_id' => $data['Template']['id'],
                                    'MappingTable.sheet_name'  => $sheet
                                ]
                            ])->first();
                    if (!isset($table["table_id"])) {
                        $checks['errors']['mapping_error'][] = "No <u>MappingTable</u> attached to the sheet <strong>$sheet</strong>";
                    } else {
                        $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
                        $highestColumm = $worksheet->getHighestColumn();

                        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumm);
                        $cell = $worksheet->getCellByColumnAndRow($highestColumnIndex, 1);
                        $val = $cell->getValue();
                        while (($val == "") && ($highestColumm > 1)) {
                            $worksheet->removeColumnByIndex($highestColumnIndex);
                            $highestColumm--;
                            $cell = $worksheet->getCellByColumnAndRow($highestColumnIndex, 1);
                            $val = $cell->getValue();
                            //error_log("dams sme rating mapping delete column " . $highestColumnIndex . " : " . json_encode($cell));
                            //$checks['errors']['col_count'][] = "The number of columns in the sheet <strong>$sheet</strong> is not correct (got <strong>".$highestColumnIndex."</strong> expected <strong>".$count_columns."</strong>)";
                        }

                        $columns = $MColumn->find('all', [
                                    'fields'     => ['MappingColumn.excel_column', 'MappingColumn.column_id', 'MappingColumn.transcode', 'MappingColumn.is_null'],
                                    'conditions' => [
                                        'MappingColumn.table_id'            => $table->table_id,
                                        'MappingColumn.excel_column >'      => 0,
                                        'MappingColumn.table_field NOT IN ' => ['action', 'error_message'],
                                    ],
                                    'order'      => ['MappingColumn.excel_column']
                                ])->all();

                        $delta = $highestColumnIndex - count($columns); // should be 6 columns every time
                        if (($delta > 0.001) || ($delta < -0.001)) {
                            $checks['errors']['col_count'][] = "The number of columns should be 6.";
                        } else {
                            //transcodification
                            //$highestRow         = $worksheet->getHighestRow(); // e.g. 10

                            /* foreach ($columns as $col) {
                              if($col['MappingColumn']['transcode']){
                              $col_to_transcode[$sheet][$col['MappingColumn']['excel_column']] = true;
                              }
                              } */
                            //$this->csvExport($filepath, $sheet, $highestRow, $highestColumnIndex, $worksheet);
                        }
                    }
                }
            }

            if ($this->noError($checks['errors'])) {
                $info = pathinfo($filepath);
                $time = time();
                $new_filepath = '/var/www/html/' . DS . 'data' . DS . 'damsv2' . DS . 'sme_rating_mapping' . DS . 'upload' . DS . $info['filename'] . "_" . $time . ".xls";
                $new_name = $info['filename'] . "_" . $time . ".xls";

                rename($filepath, $new_filepath);
                $checks['result'] = [
                    'filepath' => $new_filepath,
                    'filename' => $new_name,
                ];
                /*
                  try{
                  if(@unlink($filepath)){
                  $writer = IOFactory::createWriter($spreadsheet, "Xls");
                  $writer->save($new_filepath);
                  $checks['result'] = array(
                  'filepath' => $new_filepath,
                  'filename' => $new_name
                  );
                  $this->remove_ghost_columns($new_filepath);
                  }else{
                  $writer = IOFactory::createWriter($spreadsheet, 'Xls');
                  $writer->save($new_filepath);
                  $checks['result'] = array(
                  'filepath' => $new_filepath,
                  'filename' =>  $new_name.".xls",
                  );
                  $this->remove_ghost_columns($new_filepath);
                  }
                  }
                  catch(Exception $e)
                  {
                  error_log("excel file ".$filepath." could not be written : ".$e->getMessage());
                  $checks['errors']['other'][] = "The file <strong>".basename($filepath)."</strong> could not be saved. Please contact the support team.";
                  } */
            }
        } else {
            $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> is not readable. Probably a corrupted Excel file";
        }
        return $checks;
    }

    public function generateXlsSmeMapping($sheet, $sheetname, $headers, $filepath)
    {

        if (sizeof($sheet) > 0) {
            $spreadsheet = new Spreadsheet();

            $spreadsheet->getProperties()
                    ->setCreator("EIF DAMS TEAM")
                    ->setTitle("XLS Document")
                    ->setCategory("Dams");

            $myWorkSheet = new Worksheet($spreadsheet, $sheetname);
            //$spreadsheet->addSheet($myWorkSheet, 0);
            //$worksheet = $spreadsheet->getSheet(0);

            $h = 1;
            foreach ($headers as $header_col_name => $format) {
                $myWorkSheet->getCellByColumnAndRow($h, 1)->setValue($header_col_name);
                $h++;
            }

            $row = 0;
            $rows_count = count($sheet);

            while ($row < $rows_count) {
                $columns = array_shift($sheet);
                $i = 1;
                //$j = 1;
                foreach ($columns as $column => $data) {
                    foreach ($data as $key => $label) {
                        $myWorkSheet->getCellByColumnAndRow($i, $row + 2)->setValueExplicit($label, DataType::TYPE_STRING);
                        $i++;
                    }
                }
                $row++;
                unset($columns);
            }
            //dd('end loop');
            try {
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheetIndex = $spreadsheet->getIndex(
                        $spreadsheet->getSheetByName('Worksheet')
                );
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $writer = IOFactory::createWriter($spreadsheet, "Xls");

                $writer->save($filepath);
            } catch (Exception $e) {
                error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
                $checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
            }

            $this->File->keepLastNFiles('/var/www/html/data/damsv2/sme_rating_mapping/download', 'xls', 10);
        }
        return $filepath;
    }

    public function remove_ghost_columns($filepath)
    {
        $reader = IOFactory::createReaderForFile($filepath);
        if (!$reader) {
            return false;
        }

        $sheets = $reader->listWorksheetNames($filepath);

        $spreadsheet = $reader->load($filepath);

        foreach ($sheets as $sheet) {
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            $col_last = $highestColumnIndex;
            $ghost_col = true; //assumption
            while ($ghost_col && ($col_last > 1)) {
                $i = $col_last;
                for ($row = 1; $row <= $highestRow; ++$row) {
                    $cell = $worksheet->getCellByColumnAndRow($i, $row);
                    if ($cell->getValue() != '') {
                        $ghost_col = false;
                    }
                }
                // if for whole column there is no value
                if ($ghost_col) {
                    $worksheet->removeColumnByIndex($i);
                    $col_last--;
                }
            }
        }
        @unlink($filepath);
        $writer = new Xlsx($spreadsheet);
        $writer->save($filepath);
    }

    private function errorReceiver()
    {
        return ['errors' => [
                'missing_sheet' => [],
                'no_data'       => [],
                'col_count'     => [],
                'other'         => [],
        ]];
    }

    public function showError($errors)
    {
        if (!empty($errors['errors'])) {
            $errors = $errors['errors'];
        }
        if (!empty($errors['missing_sheet'])) {
            return implode('<br />', $errors['missing_sheet']);
        }
        if (!empty($errors['no_data'])) {
            return implode('<br />', $errors['no_data']);
        }
        if (!empty($errors['col_count'])) {
            return implode('<br />', $errors['col_count']);
        }
        if (!empty($errors['mapping_error'])) {
            return implode('<br />', $errors['mapping_error']);
        }
        if (!empty($errors['other'])) {
            return implode('<br />', $errors['other']);
        }
        if (!empty($errors['errors'])) {
            return implode('<br />', $errors['errors']);
        }
    }

    public function noError($errors)
    {
        $found = true;
        foreach ($errors as $sec => $err_section) {
            //error_log("spreadsheet error check ".$sec." : ".json_encode($err_section));
            if (!empty($err_section)) {
                $found = false;
            }
        }
        return $found;
    }

    public function waiverRead($report_id)
    {
        $sme_file_path = '/var/www/html/data/damsv2/waiver_reasons/validated/sme_exemption_' . $report_id . '.xlsx';
        $trn_file_path = '/var/www/html/data/damsv2/waiver_reasons/validated/transactions_exemption_' . $report_id . '.xlsx';
        $trn_sub_file_path = '/var/www/html/data/damsv2/waiver_reasons/validated/subtransactions_exemption_' . $report_id . '.xlsx';
        clearstatcache(true);

        if (!file_exists($sme_file_path)) {
            $sme_file_path = '/var/www/html/data/damsv2/waiver_reasons/draft/sme_exemption_' . $report_id . '.xlsx';
        }
        if (!file_exists($trn_file_path)) {
            $trn_file_path = '/var/www/html/data/damsv2/waiver_reasons/draft/transactions_exemption_' . $report_id . '.xlsx';
        }
        if (!file_exists($trn_sub_file_path)) {
            $trn_sub_file_path = '/var/www/html/data/damsv2/waiver_reasons/draft/subtransactions_exemption_' . $report_id . '.xlsx';
        }
        $result = ['SME' => [], 'TRN' => [], 'SUB' => []];
        if (!file_exists($sme_file_path) || !file_exists($trn_file_path) || !file_exists($trn_sub_file_path)) {
			return $result;
		}



        try {
            $reader = IOFactory::createReaderForFile($sme_file_path);

            if (!$reader) {
                return false;
            }
        } catch (Exception $e) {
            error_log("could not read waiver file : " . $e->getMessage());
        }

        $sheets = $reader->listWorksheetNames($sme_file_path);
        $spreadsheet = $reader->load($sme_file_path);

        foreach ($sheets as $sheet) {//should be only one sheet
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow();

            for ($row = 2; $row <= $highestRow; $row++) {
                $fiscal_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $fiscal_number = htmlentities(''.$fiscal_number);
                $country = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $country = htmlentities(''.$country);
                $error_message = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $error_message = htmlentities(''.$error_message);
                $comment = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $comment = htmlentities(''.$comment);

                if (!empty($fiscal_number) && !empty($country) && !empty($error_message)) {
                    $line = ['fiscal_number' => $fiscal_number, 'country' => $country, 'error_message' => $error_message, 'comment' => $comment];
                    array_push($result['SME'], $line);
                }
            }
        }
        try {
            $reader = IOFactory::createReaderForFile($trn_file_path);
            if (!$reader) {
                return false;
            }
        } catch (Exception $e) {
            error_log("could not read waiver file : " . $e->getMessage());
        }
        $sheets = $reader->listWorksheetNames($trn_file_path);
        $spreadsheet = $reader->load($trn_file_path);
        foreach ($sheets as $sheet) {//should be only one sheet
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow();

            for ($row = 2; $row <= $highestRow; $row++) {
                $fiscal_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $fiscal_number = htmlentities(''.$fiscal_number);
                $transaction_reference = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $transaction_reference = htmlentities(''.$transaction_reference);
                $error_message = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $error_message = htmlentities(''.$error_message);
                $comment = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $comment = htmlentities(''.$comment);

                if (!empty($fiscal_number) && !empty($transaction_reference) && !empty($error_message)) {
                    $line = ['fiscal_number' => $fiscal_number, 'transaction_reference' => $transaction_reference, 'error_message' => $error_message, 'comment' => $comment];
                    array_push($result['TRN'], $line);
                }
            }
        }
        if (file_exists($trn_sub_file_path)) {

            try {
                $reader = IOFactory::createReaderForFile($trn_sub_file_path);
                if (!$reader) {
                    return false;
                }
            } catch (Exception $e) {
                error_log("could not read waiver file : " . $e->getMessage());
            }
            $sheets = $reader->listWorksheetNames($trn_sub_file_path);
            $spreadsheet = $reader->load($trn_sub_file_path);
            foreach ($sheets as $sheet) {//should be only one sheet : SUBTRN_WAIVER
                $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
                $highestRow = $worksheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $fiscal_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $fiscal_number = htmlentities(''.$fiscal_number);
                    $transaction_reference = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $transaction_reference = htmlentities(''.$transaction_reference);
                    $subtransaction_reference = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $subtransaction_reference = htmlentities(''.$subtransaction_reference);
                    $error_message = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $error_message = htmlentities(''.$error_message);
                    $comment = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $comment = htmlentities(''.$comment);

                    if (!empty($fiscal_number) && !empty($transaction_reference) && !empty($error_message) && !empty($subtransaction_reference)) {
                        $line = ['fiscal_number' => $fiscal_number, 'transaction_reference' => $transaction_reference, 'subtransaction_reference' => $subtransaction_reference, 'error_message' => $error_message, 'comment' => $comment];
                        array_push($result['SUB'], $line);
                    }
                }
            }
        }

        return $result;
    }

    public function waiverReasonAddColumn($report_id)
    {
        clearstatcache(true);
        $filepath_sme = '/var/www/html/data/damsv2/waiver_reasons/draft/sme_exemption_' . $report_id . '.xlsx';
		if (file_exists($filepath_sme))
		{
			$reader = IOFactory::createReaderForFile($filepath_sme);
			if (!$reader) {
				return false;
			}
			$sheets = $reader->listWorksheetNames($filepath_sme);
			$spreadsheet = $reader->load($filepath_sme);
			foreach ($sheets as $sheet) {
				$worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
				$worksheet->getCellByColumnAndRow(4, 1)->setValue('exemption_reason');
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save($filepath_sme);
		}

        $filepath_trn = '/var/www/html/data/damsv2/waiver_reasons/draft/transactions_exemption_' . $report_id . '.xlsx';
		if (file_exists($filepath_trn))
		{
			$reader = IOFactory::createReaderForFile($filepath_trn);
			if (!$reader) {
				return false;
			}
			$sheets = $reader->listWorksheetNames($filepath_trn);
			$spreadsheet = $reader->load($filepath_trn);

			foreach ($sheets as $sheet) {
				$worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
				$worksheet->getCellByColumnAndRow(4, 1)->setValue('exemption_reason');
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save($filepath_trn);
		}
        $filepath_subtrn = '/var/www/html/data/damsv2/waiver_reasons/draft/subtransactions_exemption_' . $report_id . '.xlsx';
		if (file_exists($filepath_subtrn))
		{
            $reader = IOFactory::createReaderForFile($filepath_subtrn);
            if (!$reader) {
                return false;
            }
            $sheets = $reader->listWorksheetNames($filepath_subtrn);
            $spreadsheet = $reader->load($filepath_subtrn);

            foreach ($sheets as $sheet) {
                $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
                $worksheet->getCellByColumnAndRow(5, 1)->setValue('exemption_reason');
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save($filepath_subtrn);
		}
    }

    public function waiverReasonWrite($data)
    {
        $report_id = $data->getData(('Report.report_id'));
        $sme_reasons = array();
        $trn_reasons = array();
        $subtrn_reasons = array();
        if (!empty($data->getData('SME'))) {
            $sme_reasons = $data->getData('SME');
        }
        if (!empty($data->getData('TRN'))) {
            $trn_reasons = $data->getData('TRN');
        }
        if (!empty($data->getData('SUB'))) {
            $subtrn_reasons = $data->getData('SUB');
        }

        $filepath_sme = '/var/www/html/data/damsv2/waiver_reasons/draft/sme_exemption_' . $report_id . '.xlsx';
        $reader = IOFactory::createReaderForFile($filepath_sme);
        if (!$reader) {
            return false;
        }
        $sheets = $reader->listWorksheetNames($filepath_sme);
        $spreadsheet = $reader->load($filepath_sme);

        foreach ($sheets as $sheet) {
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow();

            $worksheet->getCellByColumnAndRow(4, 1)->setValue('exemption_reason');
            for ($row = 2; $row <= $highestRow; $row++) {
                $fiscal_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $fiscal_number = htmlentities(''.$fiscal_number);
                $country = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $country = htmlentities(''.$country);
                if (!empty($sme_reasons['comment_' . $fiscal_number . '___' . $country])) {
                    $worksheet->getCellByColumnAndRow(4, $row)->setValue($sme_reasons['comment_' . $fiscal_number . '___' . $country]);
                }
            }
        }
        $writer = new Xlsx($spreadsheet);
        $renamed = rename($filepath_sme, $filepath_sme . '.old.xlsx');
        $writer->save($filepath_sme);

        $filepath_trn = '/var/www/html/data/damsv2/waiver_reasons/draft/transactions_exemption_' . $report_id . '.xlsx';
        $reader = IOFactory::createReaderForFile($filepath_trn);
        if (!$reader) {
            return false;
        }
        $sheets = $reader->listWorksheetNames($filepath_trn);
        $spreadsheet = $reader->load($filepath_trn);

        foreach ($sheets as $sheet) {
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow();

            $worksheet->getCellByColumnAndRow(4, 1)->setValue('exemption_reason');
            for ($row = 2; $row <= $highestRow; $row++) {
                $fiscal_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $fiscal_number = htmlentities(''.$fiscal_number);
                $transaction_reference = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $transaction_reference = htmlentities(''.$transaction_reference);
                if (!empty($trn_reasons['comment_' . $fiscal_number . '___' . $transaction_reference])) {
                    $worksheet->getCellByColumnAndRow(4, $row)->setValue($trn_reasons['comment_' . $fiscal_number . '___' . $transaction_reference]);
                }
            }
        }
        $writer = new Xlsx($spreadsheet);
        $renamed = rename($filepath_trn, $filepath_trn . '.old.xlsx');

        $written = $writer->save($filepath_trn);
        // sub transactions : subtransactions_exemption_5470.xlsx
        $filepath_trn_sub = '/var/www/html/data/damsv2/waiver_reasons/draft/subtransactions_exemption_' . $report_id . '.xlsx';
        clearstatcache(true);
        if (file_exists($filepath_trn_sub)) {
            $reader = IOFactory::createReaderForFile($filepath_trn_sub);
            if (!$reader) {
                return false;
            }
            $sheets = $reader->listWorksheetNames($filepath_trn_sub);
            $spreadsheet = $reader->load($filepath_trn_sub);

            foreach ($sheets as $sheet) {
                $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
                $highestRow = $worksheet->getHighestRow();

                $worksheet->getCellByColumnAndRow(5, 1)->setValue('exemption_reason');
                for ($row = 2; $row <= $highestRow; $row++) {
                    $fiscal_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $fiscal_number = htmlentities(''.$fiscal_number);
                    $transaction_reference = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $transaction_reference = htmlentities(''.$transaction_reference);
                    $subtransaction_reference = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $subtransaction_reference = htmlentities(''.$subtransaction_reference);
                    if (!empty($subtrn_reasons['comment_' . $fiscal_number . '__' . $transaction_reference . '__' . $subtransaction_reference])) {
                        $worksheet->getCellByColumnAndRow(5, $row)->setValue($subtrn_reasons['comment_' . $fiscal_number . '__' . $transaction_reference . '__' . $subtransaction_reference]);
                    }
                }
            }
            $writer = new Xlsx($spreadsheet);
            $renamed = rename($filepath_trn_sub, $filepath_trn_sub . '.old.xlsx');

            $written = $writer->save($filepath_trn_sub);
        }
    }

    /*
      return the highest row in the sheet. avoid ghost rows
     */

    private function getHighestRow($worksheet)
    {
        $values = $worksheet->toArray();
        $highestRow = count($values);
        $value_found = false;
        while (($highestRow > 0) && (!$value_found)) {
            $row_values = $values[$highestRow - 1];
            foreach ($row_values as $cell) {
                if ($cell != null) {
                    $value_found = true;
                }
            }
            if (!$value_found) {
                $highestRow--;
            }
        }
        return $highestRow + 1;
    }

    private function sortDate($a, $b)
    {
        return filemtime($b) - filemtime($a);
    }

    public function getExcludedTransaction($report_id)
    {
        $result = array('count' => 0, 'fiscal_number' => array(), 'transaction_reference' => array());
        $file_pattern = '/var/www/html/data/damsv2/upload/inclusion_' . $report_id . '_v*';
        $files = glob($file_pattern);
        if (!empty($files)) {
            //usort($files, $this->sortDate);

            usort($files, function($a, $b) {
                $this->sortDate($a, $b);
            });

            $file = $files[count($files) - 1];
        }
        clearstatcache(true);
        if (!empty($file) && file_exists($file)) {
            $reader = IOFactory::createReaderForFile($file);
            if (!$reader) {
                return false;
            }
            $sheets = $reader->listWorksheetNames($file);
            if (in_array('E', $sheets)) {
                $spreadsheet = $reader->load($file);
                $worksheet = $spreadsheet->setActiveSheetIndexByName('E');
                $highestRow = $worksheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if (!empty($worksheet->getCellByColumnAndRow(1, $row)->getValue())) {
                        $result['fiscal_number'][] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    }
                    if (!empty($worksheet->getCellByColumnAndRow(2, $row)->getValue())) {
                        $result['transaction_reference'][] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    }
                    if (!empty($worksheet->getCellByColumnAndRow(2, $row)->getValue()) || !empty($worksheet->getCellByColumnAndRow(2, $row)->getValue())) {
                        $result['count']++;
                    }
                }
            }
            return $result;
        }
    }

    public function pdlrEfrontFile($filepath)
    {
        $reader = IOFactory::createReaderForFile($filepath);
        $sheets = $reader->listWorksheetNames($filepath);
        $spreadsheet = $reader->load($filepath);
        $report_data = [
            'deal_iqid'      => null,
            'operation_iqid' => null,
            'report_type'    => null,
            'period_quarter' => null,
            'period_year'    => null,
            'reception_date' => null,
            'due_date'       => null,
            'amount'         => null,
            'amount_curr'    => null,
            'amount_eur'     => null,
            'currency'       => null,
            'report_name'    => null,
        ];

        foreach ($sheets as $sheet) {
            $worksheet = $spreadsheet->setActiveSheetIndexByName($sheet);
            $highestRow = $worksheet->getHighestRow();

            $worksheet->getCellByColumnAndRow(4, 1)->setValue('exemption_reason');
            for ($row = 2; $row <= $highestRow; $row++) {
                $fiscal_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $fiscal_number = htmlentities(''.$fiscal_number);
                $transaction_reference = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $transaction_reference = htmlentities(''.$transaction_reference);
                if (!empty($trn_reasons['comment_' . $fiscal_number . '___' . $transaction_reference])) {
                    $worksheet->getCellByColumnAndRow(4, $row)->setValue($trn_reasons['comment_' . $fiscal_number . '___' . $transaction_reference]);
                }
            }
        }
        return $report_data;
    }

    public function testzip()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
                ->setCreator("EIF DAMS TEAM")
                ->setTitle("XLSX Document")
                ->setCategory("Dams/Treasury");
        $spreadsheet->setActiveSheetIndex(0);
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('frfrf');
        $worksheet->setCellValueByColumnAndRow(1, 1, "frfrf");

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save("/tmp/test_spreadsheet.xlsx");
        } catch (Exception $e) {
            error_log("excel file " . $filepath . " could not be written : " . $e->getMessage());
            //$checks['errors']['other'][] = "The file <strong>" . basename($filepath) . "</strong> could not be saved. Please contact the support team.";
        }
    }

}
