<?php

declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;
//use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Report Entity
 *
 * @property int $report_id
 * @property string|null $report_name
 * @property \Cake\I18n\FrozenDate|null $report_date
 * @property \Cake\I18n\FrozenDate|null $period_start_date
 * @property \Cake\I18n\FrozenDate|null $period_end_date
 * @property string|null $period_quarter
 * @property int|null $period_year
 * @property int $portfolio_id
 * @property int $template_id
 * @property int $status_id
 * @property string|null $validation_status
 * @property int|null $validator1
 * @property int|null $validator2
 * @property string|null $comments_validator2
 * @property int|null $status_id_umbrella
 * @property string|null $operation_iqid
 * @property int|null $invoice_id
 * @property int $owner
 * @property string|null $description
 * @property int $version_number
 * @property int $header
 * @property string $sheets
 * @property string|null $sheets_umbrella
 * @property \Cake\I18n\FrozenDate|null $reception_date
 * @property \Cake\I18n\FrozenDate|null $due_date
 * @property string|null $ccy
 * @property string|null $amount
 * @property string|null $amount_EUR
 * @property string|null $amount_ctr
 * @property string|null $input_filename
 * @property string|null $input_filename_umbrella
 * @property string|null $output_filename
 * @property int $visible
 * @property int $bulk
 * @property string|null $report_type
 * @property string|null $clawback
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property float|null $management_fees
 * @property float|null $requests
 * @property float|null $rejections
 * @property float|null $rejection_rate
 * @property float|null $interest_rate
 * @property float|null $charges
 * @property float|null $collateral_rate
 * @property string|null $comments
 * @property string|null $agreed_pv_comments
 * @property string|null $total_disbursement_comments
 * @property string|null $pkid
 * @property float|null $provisional_pv
 * @property string|null $m_files_link
 * @property string|null $inclusion_notice_received
 * @property string|null $inclusion_notice_reason
 * @property int|null $inclusion_notice_validator
 *
 * @property \App\Model\Entity\Portfolio $portfolio
 * @property \App\Model\Entity\Template $template
 * @property \App\Model\Entity\Status $status
 * @property \App\Model\Entity\Invoice $invoice
 */
class Report extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'report_name'                 => true,
        'report_date'                 => true,
        'period_start_date'           => true,
        'period_end_date'             => true,
        'period_quarter'              => true,
        'period_year'                 => true,
        'portfolio_id'                => true,
        'template_id'                 => true,
        'status_id'                   => true,
        'validation_status'           => true,
        'validator1'                  => true,
        'validator2'                  => true,
        'comments_validator2'         => true,
        'status_id_umbrella'          => true,
        'operation_iqid'              => true,
        'invoice_id'                  => true,
        'owner'                       => true,
        'description'                 => true,
        'version_number'              => true,
        'header'                      => true,
        'sheets'                      => true,
        'sheets_umbrella'             => true,
        'reception_date'              => true,
        'due_date'                    => true,
        'ccy'                         => true,
        'amount'                      => true,
        'amount_EUR'                  => true,
        'amount_ctr'                  => true,
        'input_filename'              => true,
        'input_filename_umbrella'     => true,
        'output_filename'             => true,
        'visible'                     => true,
        'bulk'                        => true,
        'report_type'                 => true,
        'clawback'                    => true,
        'created'                     => true,
        'modified'                    => true,
        'management_fees'             => true,
        'requests'                    => true,
        'rejections'                  => true,
        'rejection_rate'              => true,
        'interest_rate'               => true,
        'charges'                     => true,
        'collateral_rate'             => true,
        'comments'                    => true,
        'agreed_pv_comments'          => true,
        'total_disbursement_comments' => true,
        'pkid'                        => true,
        'provisional_pv'              => true,
        'm_files_link'                => true,
        'portfolio'                   => true,
        'template'                    => true,
        'status'                      => true,
        'invoice'                     => true,
        'inclusion_notice_received'   => true,
        'inclusion_notice_reason'     => true,
        'inclusion_notice_validator'  => true,
    ];

    protected function _getReportActionLink()
    {
        $reports = TableRegistry::getTableLocator()->get('Damsv2.Report');
        $templates = TableRegistry::getTableLocator()->get('Damsv2.Template');

        switch ($this->status_id) {
            case 1:
                $action_exploded = explode(',', $this->status->action);
                $link = "<a href='inclusion' class='change_status_1' data-report-id='" . $this->report_id . "' >" . $action_exploded[0] . "</a>";
                $previous_reports = $reports->find('all', [
                    'contain'    => ['Template'],
                    'conditions' => [
                        'Report.portfolio_id'       => $this->portfolio_id,
                        //'Report.period_end_date < ' => $this->period_start_date,//all periods
                        'Template.template_type_id' => 1, //inclusion flow
                        'Report.status_id'          => 5, //included
                ]]);
                if ($previous_reports->count() == 0) {
                    // If a previous report for the portfolio is in status “Included”, do not display an option of “No inclusion” on the dashboard for the newly generated report. (DAMS-458)
                    $link = $link . ", <a href='inclusion' class='change_status_2' data-report-id='" . $this->report_id . "' >" . $action_exploded[1] . "</a>";
                }
                $link = $this->perm->hasUpdate(array('controller'=>'Report','action'=>'inclusion'))?$link:"";
                break;
            case 2:
                $href = "/damsv2/report/inclusion-import/" . $this->report_id;
                $link = "<a href='$href'>" . $this->status->action . "</a>";

                //DAMS-946 repeat inclusion
                
                $refused_templates = [281,275];//inaf and alterna do not have repaet B sheet, jeremie bulgaria not allowed
                if (($this->portfolio->product_id != 21) && ($this->report_type == 'regular') && (!in_array($this->template_id, $refused_templates))) {// not for jeremie FRSP nor closure reports
                    $inclusion_templates = $templates->find('list', [
                                'conditions' => [
                                    'Template.template_type_id'   => 1,
                                    'Template.template_id NOT IN' => [71, 75, 77, 83, 133, 136, 139, 142, 218, 221, 252, 249, 23, 262],
                                ],
                                'keyField'   => 'template_id',
                                'valueField' => 'template_id'
                            ])->toArray();

                    $previous_reports_is_included = $reports->find('all', ['conditions' => [
                                    'Report.portfolio_id'   => $this->portfolio_id,
                                    'Report.report_id !='   => $this->report_id,
                                    'Report.template_id IN' => $inclusion_templates, //inclusion flow
                                    'Report.status_id'      => 5, //inclusion statuses
                                ], 'order'      => ['period_start_date DESC']])->first(); //latest
                    // if report is single for the period
                    $is_single = $reports->find('all', ['conditions' => [
                                    'Report.portfolio_id'   => $this->portfolio_id,
                                    'Report.report_id !='   => $this->report_id,
                                    'Report.template_id IN' => $inclusion_templates, //inclusion flow
                                    'period_start_date'     => $this->period_start_date, //same period
                                //'created' => $report['Report']['created'],//still available for the first created ?
                        ]])->first();
                    if ((empty($is_single)) && (!empty($previous_reports_is_included))) {
                        error_log("repeat last fro report " . $this->report_id);
                        $link = $link . ", <a href = 'inclusion' class='repeat_inclusion' data-id-repeat='" . $this->report_id . "' >Repeat last inclusion</a>";
                        $link = $this->perm->hasWrite(array('controller'=>'Report','action'=>'inclusion'))?$link:"";
                    }
                }
                $link = $this->perm->hasUpdate(array('controller'=>'Report','action'=>'inclusion-import'))?$link:"";
                break;
            case 3:
                $menu_identifier = $this->template->template_type_id == 1 ? 'inclusion' : 'pdlr';
                $link2 = "<a href='/damsv2/report/correction/" . $this->report_id . "/" . $menu_identifier . "'>" . $this->status->action . "</a>";
                $link = "";
				
				if ($menu_identifier == 'inclusion')
				{
					if ($this->perm->hasRead(array('controller'=>'Report','action'=>'inclusion')))
					{
						$link = $this->getErrorFileLink();
					}
					if($this->perm->hasUpdate(array('controller'=>'Report','action'=>'inclusion')))
					{
						$link = $this->getErrorFileLink() . $link2;
					}
				}
				else
				{
					if ($this->perm->hasRead(array('controller'=>'Report','action'=>'pdlr')))
					{
						$link = $this->getErrorFileLink();
					}
					if($this->perm->hasUpdate(array('controller'=>'Report','action'=>'pdlr')))
					{
						$link = $this->getErrorFileLink() . $link2;
					}
				}
                break;
            case 4:
                $href = "/damsv2/report/inclusion-validation/" . $this->report_id;
                $link1 = "<a href='$href'>" . $this->status->action . "</a>";
                $link = $this->getErrorFileLink() . $link1;
                $link = $this->perm->hasRead(array('controller'=>'Report','action'=>'inclusion-validation'))?$link:"";
                break;
            case 5:
                $href = "/damsv2/validation/inclusion-validation-ro/" . $this->report_id;
                $link = "<a href='$href'>" . $this->status->action . "</a>";
                $link = $this->perm->hasRead(array('controller'=>'Validation','action'=>'inclusion-validation-ro'))?$link:"";
                break;
            case 12:
                // case rejected
                $link = "<a href='inclusion' class='change_status_12' data-report-id='" . $this->report_id . "' >" . $this->status->action . "</a>";
                $link = $this->perm->hasUpdate(array('controller'=>'Report','action'=>'inclusion'))?$link:"";
                break;

            /* PD/LR */
            case 8:
                $href = "/damsv2/report/pdlr-import/" . $this->report_id;
                $link = "<a href='$href'>" . $this->status->action . "</a>";
                $link = $this->perm->hasUpdate(array('controller'=>'Report','action'=>'pdlr-import'))?$link:"";
                break;
            case 9:
                $href = "/damsv2/report/pdlr-validation/" . $this->report_id;
                if ($this->report_type == "Closure") {
                    $link1 = "<a href='$href'>Closure validation report</a>";
                } else {
                    $link1 = "<a href='$href'>" . $this->status->action . "</a>";
                }

                $link = $this->getErrorFileLink() . $link1;
                $link = $this->perm->hasRead(array('controller'=>'Report','action'=>'pdlr-validation'))?$link:"";
                break;
            case 10: //validated
            case 11: //capped
                $href = "/damsv2/invoice/add/" . $this->report_id;
                $link = "<a href='$href'>" . $this->status->action . "</a>";
                $link = $this->perm->hasRead(array('controller'=>'Invoice','action'=>'add'))?$link:"";
                break;

            //umbrella
            case 1:
                //received for umbrella
                $href = "/damsv2/report/inclusion-import/" . $this->report_id;
                $link = "<a href='$href' class='import_report'>" . $this->status->action . "</a>";
                $link = $this->perm->hasUpdate(array('controller'=>'Report','action'=>'inclusion-import'))?$link:"";
                break;
            /* case 7:
              //no inclusion for umbrella
              $href = "/report/split-upload/".$this->report_id;
              $link = "<a href='$href'>".$this->status->action."</a>";
              break; */
            case 22://split
                $href = "/damsv2/report/split-upload/" . $this->report_id;
                $link = "<a href='$href'>" . $this->status->action . "</a>";
                $link = $this->perm->hasRead(array('controller'=>'Report','action'=>'split-upload'))?$link:"";
                break;
            case 23://inclusion : second validation
                $href = "/damsv2/validation/inclusion-validation/" . $this->report_id;
                $link = "<a href='$href'>" . $this->status->action . "</a>";
                $link = $this->perm->hasRead(array('controller'=>'Validation','action'=>'inclusion-validation'))?$link:"";
                break;
            default://empty status action
                $link = "";
                break;
        }

        if (!in_array($this->status_id, [1, 5, 7])) {
            if (file_exists('/var/www/html/data/damsv2/reports/eif_import_file_running_report_' . $this->report_id)) {
                $link .= $this->perm->hasWrite(array('controller'=>'Report','action'=>'inclusion'))?(" <a href='inclusion' class='delete_cache' data-id-cache='" . $this->report_id . "'>delete cache</a>"):"";
            }
        }

        return $link;
    }

    private function getErrorFileLink()
    {
        $error_path = "/var/www/html/data/damsv2/error/";
        $error_file_name_excel = "error_" . $this->report_id . "_v" . $this->version_number . ".xlsx";
        $error_file_name_xml = "error_" . $this->report_id . "_v" . $this->version_number . ".xml";

        if (file_exists($error_path . $error_file_name_excel)) {
            $href2 = "/damsv2/ajax/download-file/" . $error_file_name_excel . "/error";
            $link2 = "<a href='" . $href2 . "'>Error file</a>, ";
        } elseif (file_exists($error_path . $error_file_name_xml)) {
            $href2 = "/damsv2/ajax/download-file/" . $error_file_name_xml . "/error";
            $link2 = "<a href='" . $href2 . "'>Error file</a>, ";
        } else {
            $link2 = "";
        }
        return $link2;
    }

}
