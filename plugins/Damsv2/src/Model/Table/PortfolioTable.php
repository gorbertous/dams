<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
use Cake\Datasource\ConnectionManager;

/**
 * Portfolio Model
 *
 * @property \App\Model\Table\ProductTable&\Cake\ORM\Association\BelongsTo $Product
 * @property \App\Model\Table\SmeTable&\Cake\ORM\Association\BelongsToMany $Sme
 * @property \App\Model\Table\TemplateTable&\Cake\ORM\Association\BelongsToMany $Template
 * @property \App\Model\Table\PortfolioRatesTable&\Cake\ORM\Association\HasMany $PortfolioRates
 * @property \App\Model\Table\InvoiceTable&\Cake\ORM\Association\HasMany $Invoice
 * @property \App\Model\Table\FixedRateTable&\Cake\ORM\Association\HasMany $FixedRate
 *
 * @method \App\Model\Entity\Portfolio newEmptyEntity()
 * @method \App\Model\Entity\Portfolio newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Portfolio[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Portfolio get($primaryKey, $options = [])
 * @method \App\Model\Entity\Portfolio findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Portfolio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Portfolio[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Portfolio|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Portfolio saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Portfolio[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PortfolioTable extends Table
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

        $this->setTable('portfolio');
        $this->setDisplayField('portfolio_name');
        $this->setPrimaryKey('portfolio_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Product', [
            'foreignKey' => 'product_id',
            'joinType'   => 'INNER',
        ]);

        $this->belongsTo('Damsv2.UmbrellaPortfolio', [
            'foreignKey' => 'iqid',
            'joinType'   => 'INNER',
        ]);

        $this->belongsToMany('Damsv2.Sme', [
            'foreignKey'       => 'portfolio_id',
            'targetForeignKey' => 'sme_id',
            'joinTable'        => 'sme_portfolio',
        ]);
        $this->belongsToMany('Damsv2.Template', [
            'foreignKey'       => 'portfolio_id',
            'targetForeignKey' => 'template_id',
            'joinTable'        => 'template_portfolio',
        ]);

        $this->hasMany('Damsv2.PortfolioRates', [
            'foreignKey' => 'portfolio_id',
        ]);

        $this->hasMany('Damsv2.SmeRatingMapping', [
            'foreignKey' => 'portfolio_id',
        ]);

        $this->hasMany('Damsv2.Report', [
            'foreignKey' => 'portfolio_id',
        ]);

        $this->hasMany('Damsv2.Invoice', [
            'foreignKey' => 'portfolio_id',
        ]);

        $this->hasMany('Damsv2.FixedRate', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->belongsTo('Damsv2.VUser', [
            'foreignKey' => 'owner',
        ]);
    }

    public function isEditable($portfolio_id)
    {

        $Report = TableRegistry::get('Damsv2.Report');

        $reports = $Report->find('all', [
            'contain'    => ['Template'],
            'conditions' => [
                'Report.portfolio_id'       => $portfolio_id,
                'Template.template_type_id' => 1
            ]
        ]);

        foreach ($reports as $report) {
            if ($report->status_id == 4) {
                return false;
            }
        }

        return true;
    }

    public function isEditableDraft($portfolio_id)
    {
        $Report = TableRegistry::get('Damsv2.Report');

        $reports = $Report->find('all', [
            'contain'    => ['Template'],
            'conditions' => [
                'Report.portfolio_id'       => $portfolio_id,
                'Template.template_type_id' => 1
            ]
        ]);

        foreach ($reports as $report) {
            if ($report->status_id == 23) {
                return false;
            }
        }

        return true;
    }

    /* Get the list of portfolios to which a product belongs */

    public function getPortfoliosByProductId($product_id)
    {
        $portfolios = $this->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => ['Product.product_id' => $product_id, 'Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()]
                ])->toArray();

        return $portfolios;
    }

    public function getPortfoliosByProductIdAndMandate($product_id = null, $mandate_name = null)
    {
        $conditions = ['Portfolio.iqid NOT IN' => $this->getUmbrellaIqid()];
        if (!empty($product_id)) {
            $conditions['Product.product_id'] = $product_id;
        }
        if (!empty($mandate_name)) {
            $conditions['mandate'] = $mandate_name;
        }
        $portfolios = $this->find('list', array(
            'contain'    => ['Product'],
            'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
            'keyField'   => 'portfolio_id',
            'valueField' => 'portfolio_name',
            'order'      => ['Product.name', 'Portfolio.portfolio_name'],
            'conditions' => $conditions,
                )
        );
        return $portfolios;
    }

    /* Get the list of portfolios to which a product belongs
      with umnbrella portfolio and sub portfolio
     */

    public function getPortfoliosByProductIdwithUmbrellaAndSubportfolio($product_id)
    {
        $portfolios = $this->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => ['Product.product_id' => $product_id]
                ])->toArray();

        return $portfolios;
    }

    /*
     * * return the dates matching the period given (q1, q2, ..., s1, s2) according to the product configuration (reporting_frequency)
     */

    public function getDatesFromPeriod($period, $year, $portfolio_id)
    {
        $portfolio = $this->find()->select(['Portfolio.product_id'])->where(['Portfolio.portfolio_id' => $portfolio_id])->first();

        $Product = TableRegistry::get('Damsv2.Product');
        $Products = $Product->find()->where(['Product.product_id' => $portfolio->product_id])->first();

        $six_month_delay = ($Products->reporting_frequency == 'Semi-annually (-3 months)');
        if ($six_month_delay) {
            $period .= '_spe';
        }
        switch ($period) {
            case 'Q1':
                $period_start = $year . "-01-01";
                $period_end = $year . "-03-31";
                break;
            case 'Q2':
                $period_start = $year . "-04-01";
                $period_end = $year . "-06-30";
                break;
            case 'Q3':
                $period_start = $year . "-07-01";
                $period_end = $year . "-09-30";
                break;
            case 'Q4':
                $period_start = $year . "-10-01";
                $period_end = $year . "-12-31";
                break;
            case 'S1':
                $period_start = $year . "-01-01";
                $period_end = $year . "-06-30";
                break;
            case 'S2':
                $period_start = $year . "-07-01";
                $period_end = $year . "-12-31";
                break;

            case 'S1_spe_spe':
            case 'S1_spe':
                $period_start = ($year - 1) . "-10-01";
                $period_end = $year . "-03-31";
                //$period = 'S1';
                break;
            case 'S2_spe_spe':
            case 'S2_spe':
                $period_start = $year . "-04-01";
                $period_end = $year . "-09-30";
                //$period = 'S2';
                break;
            default:
                $periods = array();
                break;
        }
        return array('period_start' => $period_start, 'period_end' => $period_end);
    }

    function getMandates()
    {
        $connection = ConnectionManager::get('default');
        $mandates_list = $connection->query("SELECT DISTINCT mandate FROM portfolio WHERE portfolio.product_id NOT IN (22,23) AND mandate <> '' ORDER BY mandate ASC")->fetchAll('assoc');
        $mandates = [];
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate['mandate']] = $mandate['mandate'];
        }
        return $mandates;
    }

    function getMandates_product($product = null)
    {
        if (empty($product)) {
            $product = 0;
        }
        $connection = ConnectionManager::get('default');
        $mandates_list = $connection->execute("SELECT DISTINCT mandate FROM portfolio WHERE portfolio.product_id =" . intval($product) . "  ORDER BY mandate ASC")->fetchAll('assoc');
        $mandates = [];
        foreach ($mandates_list as $mandate) {
            $mandates[$mandate['mandate']] = $mandate['mandate'];
        }
        return $mandates;
    }

    private function getUmbrellaIqid()
    {
        $connection = ConnectionManager::get('default');
        $umbrella_iqid = $connection->query('SELECT iqid FROM umbrella_portfolio')->fetchAll('assoc');

        $collection = new Collection($umbrella_iqid);
        $iqids = $collection->extract('iqid')->toList();
        return $iqids;
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
                ->integer('portfolio_id')
                ->allowEmptyString('portfolio_id', null, 'create')
                ->add('portfolio_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
                ->scalar('deal_name')
                ->maxLength('deal_name', 255)
                ->allowEmptyString('deal_name');

        $validator
                ->scalar('deal_business_key')
                ->maxLength('deal_business_key', 8000)
                ->allowEmptyString('deal_business_key');

        $validator
                ->scalar('iqid')
                ->maxLength('iqid', 32)
                ->allowEmptyString('iqid');

        $validator
                ->scalar('mandate')
                ->maxLength('mandate', 100)
                ->requirePresence('mandate', 'create')
                ->notEmptyString('mandate');

        $validator
                ->scalar('portfolio_name')
                ->maxLength('portfolio_name', 100)
                ->allowEmptyString('portfolio_name');

        $validator
                ->scalar('beneficiary_iqid')
                ->maxLength('beneficiary_iqid', 100)
                ->allowEmptyString('beneficiary_iqid');

        $validator
                ->scalar('beneficiary_name')
                ->maxLength('beneficiary_name', 100)
                ->allowEmptyString('beneficiary_name');

        $validator
                ->numeric('maxpv')
                ->allowEmptyString('maxpv');

        $validator
                ->numeric('agreed_pv')
                ->allowEmptyString('agreed_pv');

        $validator
                ->numeric('agreed_ga')
                ->allowEmptyString('agreed_ga');

        $validator
                ->numeric('agreed_pv_rate')
                ->allowEmptyString('agreed_pv_rate');

        $validator
                ->numeric('actual_pev')
                ->allowEmptyString('actual_pev');

        $validator
                ->numeric('minpv')
                ->allowEmptyString('minpv');

        $validator
                ->numeric('reference_volume')
                ->allowEmptyString('reference_volume');

        $validator
                ->scalar('currency')
                ->maxLength('currency', 3)
                ->allowEmptyString('currency');

        $validator
                ->scalar('fx_rate_inclusion')
                ->maxLength('fx_rate_inclusion', 300)
                ->allowEmptyString('fx_rate_inclusion');

        $validator
                ->scalar('fx_rate_pdlr')
                ->maxLength('fx_rate_pdlr', 255)
                ->allowEmptyString('fx_rate_pdlr');

        $validator
                ->numeric('guarantee_amount')
                ->allowEmptyString('guarantee_amount');

        $validator
                ->numeric('signed_amount')
                ->allowEmptyString('signed_amount');

        $validator
                ->numeric('cap_amount')
                ->allowEmptyString('cap_amount');

        $validator
                ->numeric('effective_cap_amount')
                ->allowEmptyString('effective_cap_amount');

        $validator
                ->numeric('available_cap_amount')
                ->allowEmptyString('available_cap_amount');

        $validator
                ->date('signature_date')
                ->allowEmptyDate('signature_date');

        $validator
                ->date('availability_start')
                ->allowEmptyDate('availability_start');

        $validator
                ->date('availability_end')
                ->allowEmptyDate('availability_end');

        $validator
                ->date('end_reporting_date')
                ->allowEmptyDate('end_reporting_date');

        $validator
                ->date('guarantee_termination')
                ->allowEmptyDate('guarantee_termination');

        $validator
                ->numeric('recovery_rate')
                ->allowEmptyString('recovery_rate');

        $validator
                ->numeric('call_time_to_pay')
                ->allowEmptyString('call_time_to_pay');

        $validator
                ->scalar('call_time_unit')
                ->maxLength('call_time_unit', 255)
                ->allowEmptyString('call_time_unit');

        $validator
                ->numeric('loss_rate_trigger')
                ->allowEmptyString('loss_rate_trigger');

        $validator
                ->numeric('actual_pv')
                ->allowEmptyString('actual_pv');

        $validator
                ->numeric('apv_at_closure')
                ->allowEmptyString('apv_at_closure');

        $validator
                ->numeric('actual_gv')
                ->allowEmptyString('actual_gv');

        $validator
                ->numeric('default_amount')
                ->allowEmptyString('default_amount');

        $validator
                ->scalar('country')
                ->maxLength('country', 5)
                ->requirePresence('country', 'create')
                ->notEmptyString('country');

        $validator
                ->scalar('status_portfolio')
                ->maxLength('status_portfolio', 50)
                ->notEmptyString('status_portfolio');

        $validator
                ->date('closure_date')
                ->allowEmptyDate('closure_date');

        $validator
                ->scalar('gs_deal_status')
                ->maxLength('gs_deal_status', 100)
                ->allowEmptyString('gs_deal_status');

        $validator
                ->integer('owner')
                ->notEmptyString('owner');

        $validator
                ->integer('max_trn_maturity')
                ->allowEmptyString('max_trn_maturity');

        $validator
                ->numeric('interest_risk_sharing_rate')
                ->allowEmptyString('interest_risk_sharing_rate');

        $validator
                ->date('pd_final_payment_date')
                ->allowEmptyDate('pd_final_payment_date');

        $validator
                ->integer('pd_final_payment_notice')
                ->allowEmptyString('pd_final_payment_notice');

        $validator
                ->integer('pd_decl')
                ->allowEmptyString('pd_decl');

        $validator
                ->date('in_inclusion_final_date')
                ->allowEmptyDate('in_inclusion_final_date');

        $validator
                ->integer('in_decl')
                ->allowEmptyString('in_decl');

        $validator
                ->scalar('capped')
                ->maxLength('capped', 3)
                ->notEmptyString('capped');

        $validator
                ->numeric('management_fee_rate')
                ->allowEmptyString('management_fee_rate');

        $validator
                ->numeric('cofinancing_rate')
                ->allowEmptyString('cofinancing_rate');

        $validator
                ->numeric('risk_sharing_rate')
                ->allowEmptyString('risk_sharing_rate');

        $validator
                ->scalar('guarantee_type')
                ->maxLength('guarantee_type', 250)
                ->allowEmptyString('guarantee_type');

        $validator
                ->date('effective_termination_date')
                ->allowEmptyDate('effective_termination_date');

        $validator
                ->date('inclusion_start_date')
                ->allowEmptyDate('inclusion_start_date');

        $validator
                ->date('inclusion_end_date')
                ->allowEmptyDate('inclusion_end_date');

        $validator
                ->scalar('modifications_expected')
                ->maxLength('modifications_expected', 1)
                ->allowEmptyString('modifications_expected');

        $validator
                ->scalar('m_files_link')
                ->maxLength('m_files_link', 250)
                ->allowEmptyFile('m_files_link');

        $validator
                ->scalar('kyc_embargo')
                ->allowEmptyString('kyc_embargo');

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
        $rules->add($rules->isUnique(['portfolio_id']), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['product_id'], 'Product'), ['errorField' => 'product_id']);

        return $rules;
    }

}
