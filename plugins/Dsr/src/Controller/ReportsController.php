<?php

declare(strict_types=1);

namespace Dsr\Controller;

use Cake\Event\EventInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Reports Controller
 *
 * @property \Dsr\Model\Table\ReportsTable $Reports
 * @method \Dsr\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportsController extends AppController
{
	public function initialize(): void
	{
		parent::initialize();
		//$this->loadComponent('Security');
		$this->loadComponent('Spreadsheet');
		$this->loadComponent('File');
	}

	public function beforeFilter(EventInterface $event)
	{
		parent::beforeFilter($event);
		//$this->Security->setConfig('unlockedActions', ['inclusion']);
		//$this->Security->setConfig('blackHoleCallback', 'blackhole');
	}
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index()
	{
		$this->paginate = [
			'contain' => ['Portfolios']
		];
		$reports = $this->paginate($this->Reports);
		$this->set(compact('reports'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Report id.
	 * @return \Cake\Http\Response|null|void Renders view
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id)
	{
		if (!isset($id)) {
			$id = null;
		}
		$report = $this->Reports->get($id, [
			'contain' => ['Portfolios', 'Loans'],
		]);
		$this->set(compact('report'));
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$report = $this->Reports->newEmptyEntity();
		if ($this->request->is('post')) {
			$report = $this->Reports->patchEntity($report, $this->request->getData());
			if ($this->Reports->save($report)) {
				$this->Flash->success(__('The report has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The report could not be saved. Please, try again.'));
		}
		$portfolios = $this->Reports->Portfolios->find('list', ['limit' => 200]);
		$this->set(compact('report', 'portfolios'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Report id.
	 * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function edit($id)
	{
		if (!isset($id)) {
			$id = null;
		}
		$report = $this->Reports->get($id, [
			'contain' => ['Portfolios'],
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$report = $this->Reports->patchEntity($report, $this->request->getData());
			if ($this->Reports->save($report)) {
				$this->Flash->success(__('The report has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The report could not be saved. Please, try again.'));
		}
		$portfolios = $this->Reports->Portfolios->find('list', ['limit' => 200]);
		$this->set(compact('report', 'portfolios'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Report id.
	 * @return \Cake\Http\Response|null|void Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id)
	{
		if (!isset($id)) {
			$id = null;
		}
		$this->request->allowMethod(['post', 'delete']);
		$report = $this->Reports->get($id);
		if ($this->Reports->delete($report)) {
			$this->Flash->success(__('The report has been deleted.'));
		} else {
			$this->Flash->error(__('The report could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

	/**
	 * import method
	 *
	 * @return void
	 */
	public function import()
	{
		$this->loadModel('Dsr.Portfolios');
		$portfolios = $this->Portfolios->find('list', [
			'contain'    => ['Products'],
			'fields'     => ['Products.name', 'Portfolios.name', 'Portfolios.id'],
			'keyField'   => 'id',
			'valueField' => 'name',
			'groupField' => 'product.name',
			'order'      => ['Products.name', 'Portfolios.name']
		])->toArray();

		$this->set(compact('portfolios'));


		if ($this->request->is('post')) {
			//file info processing
			$file = $this->request->getData('file');
			$file_name = $file->getClientFilename();
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);

			$filename = "Dsr_" . $this->request->getData('report_date') . '_' . $this->request->getData('portfolio_id');
			$filenameFeedback = $filename . '_feedbacks_' . date('Ymd-His') . '.xlsx';
			$file_renamed = $filename . '.' . $ext;
			$fileMovingPath = '/var/www/html' . DS . 'data' . DS . 'DSR' . DS . 'upload' . DS . $file_renamed;
			$fileFeedbacksPath = '/var/www/html' . DS . 'data' . DS . 'DSR' . DS . 'errors' . DS . $filenameFeedback;

			if ($this->File->checkFileInForm($file, $fileMovingPath)) {
				try {
					$reader = IOFactory::createReaderForFile($fileMovingPath);
				} catch (Exception $e) {
					$reader = false;
					$this->Flash->error('Spreadsheet : cannot open file ' . $fileMovingPath);
				}

				$spreadsheet = $reader->load($fileMovingPath);
				$worksheet = $spreadsheet->getActiveSheet();
				$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

				$this->validateImportFile($spreadsheet, $fileFeedbacksPath);

				//// Excel file validation ////
				if (!$this->validateImportFile($spreadsheet, $fileFeedbacksPath)) {
					$this->Flash->error('The file contains errors. Details has been added at the bottom of the <a href="/dsr/ajax/download-file/' . $filenameFeedback . '/error">file</a>', ['escape' => false]);
				} else {
					$this->loadModel('Dsr.Loans');
					//// CREATE or UPDATE the related REPORT ////
					$new_date = new \DateTime($this->request->getData('report_date'));

					$y = $new_date->format("Y");
					$m = $new_date->format("m");
					$q = floor(($m - 1) / 3) + 1;
					$q = (int)$q;
					$q = 'Q' . $q;

					$existing = $this->Reports->find('all', [
						'conditions' => [
							'portfolio_id' 	=> $this->request->getData('portfolio_id'),
							'period_quarter' => $q,
							'period_year' 	=> $y
						]
					])->first();

					if (empty($existing)) {
						$report = $this->Reports->newEmptyEntity();
						$report->portfolio_id = $this->request->getData('portfolio_id');
						$report->period_quarter = $q;
						$report->period_year = $y;
						$report->report_date = $this->request->getData('report_date');
					} else {
						$report = $this->Reports->get($existing->id);
						$report->report_date = $this->request->getData('report_date');
					}

					$saved_report = $this->Reports->save($report);
					if ($saved_report) {
						$portfolio = $this->Portfolios->find('all', ['conditions' => ['id' => $this->request->getData('portfolio_id')]])->first();
						$portfolio_name = !empty($portfolio) ? $portfolio->name : '';
						
						//create loans
						$this->loadModel('Dsr.Loans');

						for ($row = 2; $row <= $highestRow; ++$row) {

							if (empty($existing)) {
								$loandata = $this->Loans->newEmptyEntity();
							} else {
								$loandata = $this->Loans->find('all', [
									'conditions' => [
										'file_reference' => $worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue(),
										'portfolio_id' => $this->request->getData('portfolio_id')
									]
								])->first();
							}

							$loandata->report_id = $saved_report->id;
							$loandata->portfolio_id = $this->request->getData('portfolio_id');
							$loandata->deal_name = $portfolio_name;
							$loandata->loan_reference = !empty($worksheet->getCellByColumnAndRow(0, $row)->getCalculatedValue()) ? $worksheet->getCellByColumnAndRow(0, $row)->getCalculatedValue() : null;
							$loandata->file_reference = !empty($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue()) ? $worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue() : null;
							$loandata->intermediary = !empty($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue()) ? $worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue() : null;
							$loandata->gender = !empty($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue()) ? (int) $worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue() : null;
							$loandata->employment = !empty($worksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue()) ? (int) $worksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue() : null;
							$loandata->education = !empty($worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue()) ? (int) $worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue() : null;
							$loandata->age = !empty($worksheet->getCellByColumnAndRow(6, $row)->getCalculatedValue()) ?  (int) $worksheet->getCellByColumnAndRow(6, $row)->getCalculatedValue() : null;
							$loandata->specific_group = !empty($worksheet->getCellByColumnAndRow(7, $row)->getCalculatedValue()) ?  (int) $worksheet->getCellByColumnAndRow(7, $row)->getCalculatedValue() : null;
							$loandata->country = !empty($worksheet->getCellByColumnAndRow(8, $row)->getCalculatedValue()) ? $worksheet->getCellByColumnAndRow(8, $row)->getCalculatedValue() : null;
							$loandata->region = !empty($worksheet->getCellByColumnAndRow(9, $row)->getCalculatedValue()) ? $worksheet->getCellByColumnAndRow(9, $row)->getCalculatedValue() : null;
							$loandata->total_employees = !empty($worksheet->getCellByColumnAndRow(10, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(10, $row)->getCalculatedValue() : null;
							$loandata->total_male = !empty($worksheet->getCellByColumnAndRow(11, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(11, $row)->getCalculatedValue() : null;
							$loandata->total_female = !empty($worksheet->getCellByColumnAndRow(12, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(12, $row)->getCalculatedValue() : null;
							$loandata->total_less_25 = !empty($worksheet->getCellByColumnAndRow(13, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(13, $row)->getCalculatedValue() : null;
							$loandata->total_25_54 = !empty($worksheet->getCellByColumnAndRow(14, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(14, $row)->getCalculatedValue() : null;
							$loandata->total_more_55 = !empty($worksheet->getCellByColumnAndRow(15, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(15, $row)->getCalculatedValue() : null;
							$loandata->total_minority = !empty($worksheet->getCellByColumnAndRow(16, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(16, $row)->getCalculatedValue() : null;
							$loandata->total_disabled = !empty($worksheet->getCellByColumnAndRow(17, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(17, $row)->getCalculatedValue() : null;
							$loandata->expost_total_employees = !empty($worksheet->getCellByColumnAndRow(19, $row)->getCalculatedValue()) ? (double) $worksheet->getCellByColumnAndRow(19, $row)->getCalculatedValue() : null;

							$loan_saved = $this->Loans->save($loandata);
						}

						if (!empty($loan_saved)) {
							$this->Flash->success($highestRow . ' Loans saved in the database. Details <a href="/dsr/ajax/download-file/' . $filenameFeedback . '/error">file</a>', ['escape' => false]);
						} else {
							$this->Flash->error('Any Loan to save OR only ex-post updated', 'flash/defaul');
						}
					}
				}
			}
		}
	}

	private function validateImportFile(&$objPHPExcel, $outputPath)
	{

		$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
		$highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
		$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();

		$errors = $notifications = $captions = $dbupdates = array();
		$msgtypes = array(
			'document_format' => array('msg' => 'Uploaded file doesnt match the required format'),
			'same_file_reference_in_file' => array('msg' => 'Multiple occurences of the same File Reference', 'color' => 'E55454'),
			'same_file_reference_in_db' => array('msg' => 'Occurences of File Reference found in the database for the same portfolio', 'color' => 'E27F14'),
			'both_range_DJ_KR' => array('msg' => 'Either (D:J) or (K:R) must be filled, not both', 'color' => 'DF8BE5'),
			'loan_reference_double' => array('msg' => 'Multiple occurences of the same Load Reference', 'color' => 'B48BE5'),
			'filereference_updated' => array('msg' => 'File Reference has been updated in the database', 'color' => 'BDE58B'),
			'db_expost_total_employees_not_empty' => array('msg' => 'Expost Total Employees field is already filled in the database', 'color' => 'E0D538'),
			'db_expost_total_employees_not_found' => array('msg' => 'File Reference not found in the database', 'color' => 'E0D538'),
		);

		$this->loadModel('Dsr.Loans');

		//// Performance improvment: Convert Excel table to php arrays: one global, one cell index from phpexcel cell objects ////
		$datas = $cellindex = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			$line = array();
			for ($col = 1; $col <= $highestColumnIndex; $col++) {
				$cell = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row);
				$line[] = array(
					'row' => $row, 'col' => $col, 'colstr' => $cell->getColumn(), //save cell position in original file, we might need it later...
					'value' => $cell->getCalculatedValue()
				);

				if (!isset($cellindex[$row])) $cellindex[$row] = array();
				$cellindex[$row][$col] = $cell;
			}
			$datas[] = $line;
		}

		//// Check 1 - The file must include 2 tables: ex-ante (A:R) & ex-post(T:U) ////
		if (empty($highestColumn) || strtoupper($highestColumn) != 'U') {
			$errors[] = array('type' => 'document_format', 'details' => 'Number of columns doesnt match the requirements (A:U)');
		} else {
			$separator = Coordinate::columnIndexFromString('S') - 1;
			if (!empty($datas[0][$separator]['value'])) {
				$errors[] = array('type' => 'document_format', 'details' => 'The file must include 2 tables: ex-ante (A:R) & ex-post(T:U)');
			}
		}

		//// Check 2 - Datas must always start at line 2 ////
		if (empty($datas[1][0]['value']) && empty($datas[1][20]['value'])) {
			$errors[] = array('type' => 'document_format', 'details' => 'Datas must always start at line 2');
		}

		//// Check 3 - A, B & C columns are required OR T, U ////
		foreach ($datas as $rownum => $row) {
			if ($rownum) {
				$empty = false;
				if (count($row) < 3) {
					$empty = true;
				} else {
					if (empty($row[0]['value']) or empty($row[1]['value']) or empty($row[2]['value'])) {
						if (empty($row[20]['value'])) {
							$empty = true;
						}
					}
				}
				if ($empty) {
					$errors[] = array('type' => 'document_format', 'details' => 'A, B & C columns are required');
					break;
				}
			}
		}

		//// Further checks need a valid document format: continue only if there is no error ////
		if (empty($errors)) {
			//// Check 4 - column B must be unique within the file and the database (associated with the current portfolio) ////
			$col_b_check = array();
			foreach ($datas as $rownum => $row) if ($rownum) { //skip first title line
				$cell = $row[1];
				if (!empty($cell['value'])) {
					if (isset($col_b_check[$cell['value']])) {
						$errors[] = array('type' => 'same_file_reference_in_file', 'row' => $cell['row'], 'col' => $cell['col'], 'cell' => $cell, 'details' => $col_b_check[$cell['value']]);
					} else {
						$col_b_check[$cell['value']] = $cell;
						//check in the DB
						$loans = $this->Loans->find('all', [
							'conditions' => [
								'file_reference' => $cell['value'],
								'portfolio_id' => $this->request->getData('portfolio_id')
							]
						])->first();

						if (!empty($loans)) {
							$errors[] = array('type' => 'same_file_reference_in_db', 'row' => $cell['row'], 'col' => $cell['col'], 'cell' => $cell, 'details' => reset($loans));
						}
					}
				}
			}

			//// Check 5 - Either (D:J) or (K:R) must be filled, not both ////
			foreach ($datas as $rownum => $row) if ($rownum) { //skip first title line
				$DJ = $KR = $TU = false;
				for ($col = 3; $col <= 9; $col++) {
					if (!empty($row[$col]['value'])) {
						$DJ = true;
					}
				}

				for ($col = 10; $col <= 17; $col++) {
					if (!empty($row[$col]['value'])) {
						$KR = true;
					}
				}

				for ($col = 19; $col <= 20; $col++) {
					if (!empty($row[$col]['value']) or ($row[$col]['value'] == 0)) {
						$TU = true;
					}
				}

				if (!empty($DJ) && !empty($KR) && !empty($TU)) {
					$errors[] = array('type' => 'both_range_DJ_KR', 'row' => $rownum, 'cell' => $row[3], 'cellend' => $row[17]);
				}
			}


			//// Check 6 - column A must be unique within the file. If found within the DB, update the file_reference with column B value ////
			$col_a_check = array();
			foreach ($datas as $rownum => $row) {
				if ($rownum) { //skip first title line
					$cell = $row[0];
					if (!empty($cell['value'])) {
						if (isset($col_a_check[$cell['value']])) {
							$errors[] = array('type' => 'loan_reference_double', 'row' => $cell['row'], 'col' => $cell['col'], 'cell' => $cell, 'details' => $col_a_check[$cell['value']]);
						} else {
							$col_a_check[$cell['value']] = $cell;

							//check in the DB
							$loans = $this->Loans->find('all', ['conditions' => [
								'loan_reference' => $cell['value'],
								'portfolio_id' => $this->request->getData('portfolio_id')
							]]);

							//update found entries: filereference = col B value
							if ($loans->count() > 0 && !empty($row[1]['value'])) {
								foreach ($loans as $loan) {
									$loan->file_reference = $row[1]['value'];
									$up = array('Loans' => array(
										'id' => $loan->id,
										'file_reference' => $row[1]['value'],
									));

									$notifications = array('type' => 'filereference_updated', 'row' => $cell['row'], 'col' => $cell['col'], 'cell' => $cell, 'cellend' => $row[1], 'details' => $row[1]);
									$dbupdates[] = array('model' => 'Loans', 'datas' => $up, 'notification' => $notifications);
									//$this->Loan->save($up);
								}
							}
						}
					}
				}
			}

			//// Check 7 - If col U value found in DB, update EMPTY expost_total_employees value according to col T ////
			foreach ($datas as $rownum => $row) {
				if ($rownum) { //skip first title line
					if ((!empty($row[19]['value']) or $row[19]['value'] == 0) && !empty($row[20]['value'])) {
						$loans = $this->Loans->find('all', ['conditions' => [
							'file_reference' => $row[1]['value'],
							'portfolio_id' => $this->request->getData('portfolio_id')
						]]);

						//not in database... is it in the same file?
						/* if ($loans->count() == 0) {
							$found = false;
							foreach ($datas as $subrownum => $subrow) {
								if ($subrownum) {
									if (!empty($subrow[1]['value']) && $subrow[1]['value'] == $row[1]['value']) {
										$found = true;
										break;
									}
								}
							}
							//not even in the file: ADD ERROR
							if (!$found) {
								$errors[] = array('type' => 'db_expost_total_employees_not_found', 'row' => $row[20]['row'], 'col' => $row[20]['col'], 'cell' => $row[20], 'details' => $row[19]);

								//found in the file. update it as soon as it was created
							} else {
								$up = array('Loans' => array(
									'expost_total_employees' => $row[19]['value'],
								));
								$conditions = array('file_reference' => $row[20]['value']);
								$dbupdates[] = array('model' => 'Loans', 'datas' => $up, 'conditions' => $conditions);
							}

							//found in the database, update it (later) !
						} else { */

						if ($loans->count() > 0) {
							foreach ($loans as $loan) {
								if (empty($loan->expost_total_employees)) {
									$up = array('Loans' => array(
										'id' => $loan->id,
										'expost_total_employees' => $row[19]['value'],
									));
									$dbupdates[] = array('model' => 'Loans', 'datas' => $up);
									//$this->Loan->save($up);
								} else {
									$errors[] = array('type' => 'db_expost_total_employees_not_empty', 'row' => $row[19]['row'], 'col' => $row[19]['col'], 'cell' => $row[19], 'details' => $row[20]);
								}
							}
						}
					}
				}
			}
		}

		//// IF NO ERRORS, PERFORM DB UPDATES ////
		/* if (empty($errors)) {
			if (!empty($dbupdates)) {
				foreach ($dbupdates as $dbupdate) {
					if (!empty($dbupdate['datas']) && !empty($dbupdate['model'])) {
						$uplist = array();
						$model = null;
						switch ($dbupdate["model"]) {
							case 'Loans':
								$model = $this->Loan;
								break;

							case 'Products':
								$model = $this->Product;
								break;

							case 'Portfolios':
								$model = $this->Portfolio;
								break;

							case 'Reports':
								$model = $this->Report;
								break;
						}
						// no conditions, just add the update to the list
						if (empty($dbupdate['conditions'])) {
							$uplist = array($dbupdate['datas']);

							//conditions found, loop on results and build up update list
						} else {

							$results = $model->find('all', array('conditions' => $dbupdate['conditions'])); // replaced by switch above
							if (!empty($results)) {
								foreach ($results as $result) {
									$up = $dbupdate['datas'];
									if (!empty($result[$dbupdate['model']]['id'])) $up[$dbupdate['model']]['id'] = $result[$dbupdate['model']]['id'];
									$uplist[] = $up;
								}
							}
						}

						//proceed
						$done = false;
						if (!empty($uplist)) foreach ($uplist as $up) {
							if ($model->save($up)) {
								$done = true;
							}
						}

						//if done, check for a notification
						if (!empty($done) && !empty($dbupdate['notification'])) {
							$notifications[] = $dbupdate['notification'];
						}
					}
				}
			}
		} */

		//// UPDATE Excel File: ERRORS ////
		if (!empty($errors)) {

			foreach ($errors as $error) {
				$color = 'FF0000';
				if (!empty($error['type']) && !empty($msgtypes[$error['type']])) {
					$type = $msgtypes[$error['type']];
					if (!empty($type['color'])) $color = $type['color'];
					$captions[$error['type']] = $error['type'];
				}

				//background color for the cell containing error
				$range = null;
				if (!empty($error['cell']['colstr']) && !empty($error['cell']['row'])) {
					$range = $error['cell']['colstr'] . $error['cell']['row'];

					if (!empty($error['cellend']['colstr']) && !empty($error['cellend']['row'])) {
						$range .= ':' . $error['cellend']['colstr'] . $error['cellend']['row'];
					}
				}

				if (!empty($range)) {
					$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray(array('fill' => array(
						'type' => Fill::FILL_SOLID,
						'color' => array('rgb' => $color)
					)));
				}

				//background color for the related 'details' cell
				$rangedetails = null;
				if (!empty($error['details']['colstr']) && !empty($error['details']['row'])) {
					$rangedetails = $error['details']['colstr'] . $error['details']['row'];

					if (!empty($error['detailsend']['colstr']) && !empty($error['detailsend']['row'])) {
						$rangedetails .= ':' . $error['detailsend']['colstr'] . $error['detailsend']['row'];
					}
				}
				if (!empty($rangedetails)) {
					$objPHPExcel->getActiveSheet()->getStyle($rangedetails)->applyFromArray(array('fill' => array(
						'type' => Fill::FILL_SOLID,
						'color' => array('rgb' => $color)
					)));
				}
			}
		}

		//// UPDATE Excel File: NOTIFICATIONS ////
		if (!empty($notifications)) {
			foreach ($notifications as $notification) {
				$color = 'B2EFFF';
				if (!empty($notification['type']) && !empty($msgtypes[$notification['type']])) {
					$type = $msgtypes[$notification['type']];
					if (!empty($type['color'])) $color = $type['color'];
					$captions[$notification['type']] = $notification['type'];
				}

				$range = null;
				if (!empty($notification['cell']['colstr']) && !empty($notification['cell']['row'])) {
					$range = $notification['cell']['colstr'] . $notification['cell']['row'];

					if (!empty($notification['cellend']['colstr']) && !empty($notification['cellend']['row'])) {
						$range .= ':' . $notification['cellend']['colstr'] . $notification['cellend']['row'];
					}
				}
				if (!empty($range)) {
					$objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray(array('fill' => array(
						'type' => Fill::FILL_SOLID,
						'color' => array('rgb' => $color)
					)));
				}
			}
		}

		//// UPDATE Excel File: document_format ERRORS ////
		$pos = $highestRow + 1;
		if (!empty($errors)) {
			$docforpos = null;
			foreach ($errors as $error) {
				if (!empty($error['type']) && $error['type'] == 'document_format') {
					$pos++;
					if (empty($docforpos)) {
						$docforpos = $pos;
						$pos++;
					}
					$objPHPExcel->getActiveSheet()->setCellValue('C' . $pos, '- ' . $error['details']);
				}
			}
			if (!empty($docforpos)) {
				$type = $msgtypes[$error['type']];
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $docforpos, $type['msg']);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $docforpos)->applyFromArray(array('fill' => array(
					'type' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'ff0000')
				)));
				$pos++;
			}
		}
		//// UPDATE Excel File: CAPTIONS ////
		if (!empty($captions)) foreach ($captions as $caption) {
			$pos++;

			if (!empty($msgtypes[$caption])) {
				$type = $msgtypes[$caption];
				if ($caption != 'document_format') { //document_format errors are displayed in a specific manner earlier
					$objPHPExcel->getActiveSheet()->setCellValue('C' . $pos, $type['msg']);
					if (!empty($type['color'])) {
						$objPHPExcel->getActiveSheet()->getStyle('B' . $pos)->applyFromArray(array('fill' => array(
							'type' => Fill::FILL_SOLID,
							'color' => array('rgb' => $type['color'])
						)));
					}
				}
			}
		}

		//// UPDATE Excel File: SAVE ////
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
		$objWriter->save($outputPath);

		return empty($errors);
	}

	public function dsrView()
	{
		$this->loadModel('Dsr.Vdsrreport');
		$query = $this->Vdsrreport->find('all', [
			'contain'    => ['Portfolios']
		]);

		$reports = $this->paginate($query);

		$this->set('reports', $reports);
	}
}
