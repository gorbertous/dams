<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PortfolioFixture
 */
class PortfolioFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'portfolio';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'deal_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'deal_business_key' => ['type' => 'string', 'length' => 8000, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'iqid' => ['type' => 'string', 'length' => 32, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'mandate' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'portfolio_name' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'beneficiary_iqid' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'beneficiary_name' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'maxpv' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'agreed_pv' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'agreed_ga' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'agreed_pv_rate' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'actual_pev' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'minpv' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'reference_volume' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'currency' => ['type' => 'string', 'length' => 3, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'fx_rate_inclusion' => ['type' => 'string', 'length' => 300, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'fx_rate_pdlr' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'guarantee_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'signed_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'cap_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'effective_cap_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'available_cap_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'signature_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'availability_start' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'availability_end' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'end_reporting_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'guarantee_termination' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'recovery_rate' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'call_time_to_pay' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'call_time_unit' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'loss_rate_trigger' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'actual_pv' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'apv_at_closure' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'actual_gv' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'default_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'country' => ['type' => 'string', 'length' => 5, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'product_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'status_portfolio' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => 'OPEN', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'closure_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'gs_deal_status' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'owner' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'max_trn_maturity' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'interest_risk_sharing_rate' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'pd_final_payment_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'pd_final_payment_notice' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pd_decl' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'in_inclusion_final_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'in_decl' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'capped' => ['type' => 'string', 'length' => 3, 'null' => false, 'default' => 'YES', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'management_fee_rate' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'cofinancing_rate' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'risk_sharing_rate' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'guarantee_type' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'effective_termination_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'inclusion_start_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'inclusion_end_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modifications_expected' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'm_files_link' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'kyc_embargo' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'owner' => ['type' => 'index', 'columns' => ['owner'], 'length' => []],
            'product_id' => ['type' => 'index', 'columns' => ['product_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['portfolio_id'], 'length' => []],
            'portfolio_id' => ['type' => 'unique', 'columns' => ['portfolio_id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'portfolio_id' => 1,
                'deal_name' => 'Lorem ipsum dolor sit amet',
                'deal_business_key' => 'Lorem ipsum dolor sit amet',
                'iqid' => 'Lorem ipsum dolor sit amet',
                'mandate' => 'Lorem ipsum dolor sit amet',
                'portfolio_name' => 'Lorem ipsum dolor sit amet',
                'beneficiary_iqid' => 'Lorem ipsum dolor sit amet',
                'beneficiary_name' => 'Lorem ipsum dolor sit amet',
                'maxpv' => 1,
                'agreed_pv' => 1,
                'agreed_ga' => 1,
                'agreed_pv_rate' => 1,
                'actual_pev' => 1,
                'minpv' => 1,
                'reference_volume' => 1,
                'currency' => 'L',
                'fx_rate_inclusion' => 'Lorem ipsum dolor sit amet',
                'fx_rate_pdlr' => 'Lorem ipsum dolor sit amet',
                'guarantee_amount' => 1,
                'signed_amount' => 1,
                'cap_amount' => 1,
                'effective_cap_amount' => 1,
                'available_cap_amount' => 1,
                'signature_date' => '2021-03-03',
                'availability_start' => '2021-03-03',
                'availability_end' => '2021-03-03',
                'end_reporting_date' => '2021-03-03',
                'guarantee_termination' => '2021-03-03',
                'recovery_rate' => 1,
                'call_time_to_pay' => 1,
                'call_time_unit' => 'Lorem ipsum dolor sit amet',
                'loss_rate_trigger' => 1,
                'actual_pv' => 1,
                'apv_at_closure' => 1,
                'actual_gv' => 1,
                'default_amount' => 1,
                'country' => 'Lor',
                'product_id' => 1,
                'status_portfolio' => 'Lorem ipsum dolor sit amet',
                'closure_date' => '2021-03-03',
                'gs_deal_status' => 'Lorem ipsum dolor sit amet',
                'owner' => 1,
                'max_trn_maturity' => 1,
                'interest_risk_sharing_rate' => 1,
                'created' => '2021-03-03 15:53:00',
                'modified' => 1614786780,
                'pd_final_payment_date' => '2021-03-03',
                'pd_final_payment_notice' => 1,
                'pd_decl' => 1,
                'in_inclusion_final_date' => '2021-03-03',
                'in_decl' => 1,
                'capped' => 'L',
                'management_fee_rate' => 1,
                'cofinancing_rate' => 1,
                'risk_sharing_rate' => 1,
                'guarantee_type' => 'Lorem ipsum dolor sit amet',
                'effective_termination_date' => '2021-03-03',
                'inclusion_start_date' => '2021-03-03',
                'inclusion_end_date' => '2021-03-03',
                'modifications_expected' => 'L',
                'm_files_link' => 'Lorem ipsum dolor sit amet',
                'kyc_embargo' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
