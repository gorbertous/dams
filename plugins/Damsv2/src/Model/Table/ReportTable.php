<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Cache\Cache;
use KubAT\PhpSimple\HtmlDomParser;
use Cake\ORM\TableRegistry;

/**
 * Report Model
 *
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \App\Model\Table\TemplatesTable&\Cake\ORM\Association\BelongsTo $Templates
 * @property \App\Model\Table\StatusesTable&\Cake\ORM\Association\BelongsTo $Statuses
 * @property \App\Model\Table\InvoicesTable&\Cake\ORM\Association\BelongsTo $Invoice
 *
 * @method \App\Model\Entity\Report newEmptyEntity()
 * @method \App\Model\Entity\Report newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Report[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Report get($primaryKey, $options = [])
 * @method \App\Model\Entity\Report findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Report patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Report[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Report|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Report saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('report');
        $this->setDisplayField('report_id');
        $this->setPrimaryKey('report_id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('EnhancedFinder');

        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Damsv2.Template', [
            'foreignKey' => 'template_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Damsv2.Status', [
            'foreignKey' => 'status_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Damsv2.Invoice', [
            'foreignKey' => 'invoice_id',
        ]);

        $this->belongsTo('Damsv2.StatusUmbrella', [
            'foreignKey' => 'status_id_umbrella',
            'joinType'   => 'INNER',
        ]);

        $this->belongsTo('Damsv2.VUser', [
            'foreignKey' => 'owner',
            'bindingKey' => 'id',
        ]);
    }

    /**
     * Custom finders for project and related tables data
     *
     */
    public function findPortfolioResults(Query $query, array $options)
    {
        $query = $this->find('all')
                ->where($options)
                ->contain(['Report', 'Portfolio']);
        return $query;
    }

    public function getWarningsPortfolioVolume($report_id)
    {
        $report = $this->get($report_id, [
            'contain' => ['Portfolio'],
        ]);
        //$report = $this->get($report_id);
        $content = Cache::read('inclusion_validation_report_' . $report_id);


        $warning_agreed_portfolio_volume = false;
        $apvExceeded = false;
        $apvDecrease = false;
        $mgv = false;
        $agreed_ga = false;
        $total_principal_disbursement = false;
        $aga_nonCOVID19 = false;
        $covid_19_enhanced_rate_transactions = false;
        if ($content) {

            $dom = HtmlDomParser::str_get_html($content);
            $table = $dom->find('table');

            $tmp_Actual_Portfolio_Volume = null;
            $Actual_Portfolio_Volume = null;
            $tmp_Maximum_Portfolio_Volume = null;
            $Maximum_Portfolio_Volume = null;
            $warning = $dom->find('.warning');

            foreach ($warning as $m) {
                $val = trim($m->innertext);
                if ($val == 'w4') {
                    //condition Agreed Portfolio Volume (AgPV)
                    $warning_agreed_portfolio_volume = true;
                }
                if ($val == 'w5') {
                    //condition Maximum Portfolio Volume (MPV)
                    $apvExceeded = true;
                }
                if ($val == 'w6') {
                    //condition APV decrease and cap reached 
                    $apvDecrease = true;
                }
                if ($val == 'w7') {
                    //condition Maximum Guaranteed Volume (MGV)
                    $mgv = true;
                }
                if ($val == 'w8') {
                    //condition agreed garantee amount
                    $agreed_ga = true;
                }
                if ($val == 'w9') {
                    //condition Total principal disbursement
                    $total_principal_disbursement = true;
                }
                if ($val == 'w10') {
                    //condition Total principal disbursement
                    $aga_nonCOVID19 = true;
                }
                if ($val == 'w10_warning') {
                    //condition Total principal disbursement
                    $covid_19_enhanced_rate_transactions = true;
                }
            }
        }
        $product = $report->portfolio->product_id;

        if ($apvDecrease && $this->productForApvdecrease($product, $report_id)) {
            $apvDecrease = true;
        } else {
            $apvDecrease = false;
        }

        //products id : 'PRSL' : 4, EPMF FCP : 7, EREM CBSI : 17
        $prsl_products = [4, 7, 17];
        if ($apvExceeded && in_array($product, $prsl_products)) {
            $apvExceeded = false;
        }

        //mgv is applicable to InnovFin and COSME products only
        $innofvinANDcosmeProducts = [5, 6, 13, 15];
        if ($mgv && in_array($product, $innofvinANDcosmeProducts)) {
            $mgv = true;
        } else {
            $mgv = false;
        }
        // Agreed GA is applicable only to COSME and innovfin
        $cosmeProducts = [6, 5];
        if ($agreed_ga && in_array($product, $cosmeProducts)) {
            $agreed_ga = true;
        } else {
            $agreed_ga = false;
        }
        //total_principal_disbursement
        $mandate = $report->portfolio->mandate;

        $total_principal_disbursement_mantade = ['ESIF-Silesia'];
        if ($total_principal_disbursement && in_array($mandate, $total_principal_disbursement_mantade)) {
            $total_principal_disbursement = true;
        } else {
            $total_principal_disbursement = false;
        }
        //aga_nonCOVID19 is applicable to portfolio with theme COVID19
        if ($aga_nonCOVID19) {
            $portfoliorate = $this->getTableLocator()->get('Damsv2.PortfolioRates');
            $theme = $portfoliorate->find('all', ['conditions' => ['portfolio_id' => $report->portfolio_id], 'fields' => 'theme', 'recursive' => -1])->first();

            if (!empty($theme) && ($theme->theme == 'COVID19')) {
                $aga_nonCOVID19 = true;
                if ($covid_19_enhanced_rate_transactions) {
                    $covid_19_enhanced_rate_transactions = true;
                }
            } else {
                $aga_nonCOVID19 = false;
                $covid_19_enhanced_rate_transactions = false;
            }
        }
        return ['apvExceeded' => $apvExceeded, 'warning_agreed_portfolio_volume' => $warning_agreed_portfolio_volume, 'apvDecrease' => $apvDecrease, 'mgv' => $mgv, 'agreed_ga' => $agreed_ga, 'total_principal_disbursement' => $total_principal_disbursement, 'aga_nonCOVID19' => $aga_nonCOVID19, 'covid_19_enhanced_rate_transactions' => $covid_19_enhanced_rate_transactions];
    }

    function productForApvdecrease($product_id, $report_id)
    {
        $valid = false; //return value
        //This functionality is applicable to all capped guarantee products, excluding deals for which inclusion_end_date is empty.
        $this->Product = TableRegistry::get('Damsv2.Product');
        $product_ok = $this->Product->find('all', [
                    'conditions' => ['product_id'   => $product_id,
                        'capped'       => 'YES',
                        'product_type' => 'guarantee',
            ]])->first();

        if (!empty($product_ok)) {

            $report_ok = $this->find('all', [
                        'contain'    => ['Portfolio'],
                        'conditions' => ['report_id' => $report_id, 'inclusion_end_date is not ' => null]
                    ])->first();

            if (!empty($report_ok)) {
                $valid = true;
            } else {
                error_log("dams productForApvdecrease deal has bad end_inclusion_date :" . $report_id);
            }
        } else {
            error_log("dams productForApvdecrease product not in list :" . $product_id);
        }

        return $valid;
    }

    //return the earliest latest year of report
    public function getLastestYearFromPortofolioId($portfolio_id)
    {
        $template_id = 1;
        $res = $this->find('all', ['conditions' => [
                        'portfolio_id' => $portfolio_id,
                        'template_id'  => $template_id],
                    'fields'     => 'period_year',
                    'order'      => ['period_year DESC'],
                    'recursive'  => -1
                ])->first();

        return $res;
    }

    public function updateStatus($report_id, $status_id)
    {
        $report = [];
        $report['Report'] = ['report_id' => $report_id, 'status_id' => $status_id];
        $this->save($report);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->integer('report_id')
                ->allowEmptyString('report_id', null, 'create')
                ->add('report_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
                ->scalar('report_name')
                ->maxLength('report_name', 100)
                ->allowEmptyString('report_name');

        $validator
                ->date('report_date')
                ->allowEmptyDate('report_date');

        $validator
                ->date('period_start_date')
                ->allowEmptyDate('period_start_date');

        $validator
                ->date('period_end_date')
                ->allowEmptyDate('period_end_date');

        $validator
                ->scalar('period_quarter')
                ->maxLength('period_quarter', 2)
                ->allowEmptyString('period_quarter');

        $validator
                ->integer('period_year')
                ->allowEmptyString('period_year');

        $validator
                ->scalar('validation_status')
                ->maxLength('validation_status', 255)
                ->allowEmptyString('validation_status');

        $validator
                ->integer('validator1')
                ->allowEmptyString('validator1');

        $validator
                ->integer('validator2')
                ->allowEmptyString('validator2');

        $validator
                ->scalar('comments_validator2')
                ->maxLength('comments_validator2', 255)
                ->allowEmptyString('comments_validator2');

        $validator
                ->integer('status_id_umbrella')
                ->allowEmptyString('status_id_umbrella');

        $validator
                ->scalar('operation_iqid')
                ->maxLength('operation_iqid', 100)
                ->allowEmptyString('operation_iqid');

        $validator
                ->integer('owner')
                ->notEmptyString('owner');

        $validator
                ->scalar('description')
                ->allowEmptyString('description');

        $validator
                ->integer('version_number')
                ->notEmptyString('version_number');

        $validator
                ->notEmptyString('header');

        $validator
                ->scalar('sheets')
                ->maxLength('sheets', 255)
                ->notEmptyString('sheets');

        $validator
                ->scalar('sheets_umbrella')
                ->maxLength('sheets_umbrella', 200)
                ->allowEmptyString('sheets_umbrella');

        $validator
                ->date('reception_date')
                ->allowEmptyDate('reception_date');

        $validator
                ->date('due_date')
                ->allowEmptyDate('due_date');

        $validator
                ->scalar('ccy')
                ->maxLength('ccy', 3)
                ->allowEmptyString('ccy');

        $validator
                ->decimal('amount')
                ->allowEmptyString('amount');

        $validator
                ->decimal('amount_EUR')
                ->allowEmptyString('amount_EUR');

        $validator
                ->decimal('amount_ctr')
                ->allowEmptyString('amount_ctr');

        $validator
                ->scalar('input_filename')
                ->maxLength('input_filename', 255)
                ->allowEmptyFile('input_filename');

        $validator
                ->scalar('input_filename_umbrella')
                ->maxLength('input_filename_umbrella', 200)
                ->allowEmptyFile('input_filename_umbrella');

        $validator
                ->scalar('output_filename')
                ->maxLength('output_filename', 255)
                ->allowEmptyFile('output_filename');

        $validator
                ->integer('visible')
                ->notEmptyString('visible');

        $validator
                ->notEmptyString('bulk');

        $validator
                ->scalar('report_type')
                ->maxLength('report_type', 200)
                ->allowEmptyString('report_type');

        $validator
                ->scalar('clawback')
                ->maxLength('clawback', 1)
                ->allowEmptyString('clawback');

        $validator
                ->numeric('management_fees')
                ->allowEmptyString('management_fees');

        $validator
                ->numeric('requests')
                ->allowEmptyString('requests');

        $validator
                ->numeric('rejections')
                ->allowEmptyString('rejections');

        $validator
                ->numeric('rejection_rate')
                ->allowEmptyString('rejection_rate');

        $validator
                ->numeric('interest_rate')
                ->allowEmptyString('interest_rate');

        $validator
                ->numeric('charges')
                ->allowEmptyString('charges');

        $validator
                ->numeric('collateral_rate')
                ->allowEmptyString('collateral_rate');

        $validator
                ->scalar('comments')
                ->allowEmptyString('comments');

        $validator
                ->scalar('agreed_pv_comments')
                ->allowEmptyString('agreed_pv_comments');

        $validator
                ->scalar('total_disbursement_comments')
                ->allowEmptyString('total_disbursement_comments');

        $validator
                ->scalar('pkid')
                ->maxLength('pkid', 32)
                ->allowEmptyString('pkid');

        $validator
                ->numeric('provisional_pv')
                ->allowEmptyString('provisional_pv');

        $validator
                ->scalar('m_files_link')
                ->maxLength('m_files_link', 250)
                ->allowEmptyFile('m_files_link');

        $validator
                ->scalar('inclusion_notice_received')
                ->maxLength('inclusion_notice_received', 5)
                ->allowEmptyString('inclusion_notice_received');

        $validator
                ->allowEmptyString('inclusion_notice_reason');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['report_id']), ['errorField' => 'report_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolio'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['template_id'], 'Template'), ['errorField' => 'template_id']);
        $rules->add($rules->existsIn(['status_id'], 'Status'), ['errorField' => 'status_id']);
        $rules->add($rules->existsIn(['invoice_id'], 'Invoice'), ['errorField' => 'invoice_id']);

        return $rules;
    }

}
