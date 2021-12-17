<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InvoicesFixture
 */
class InvoicesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'invoice_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'invoice_owner' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'invoice_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'due_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'expected_payment_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'accounting_payment_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'contract_currency' => ['type' => 'string', 'length' => 3, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'amount_curr' => ['type' => 'decimal', 'length' => 11, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'amount_eur' => ['type' => 'decimal', 'length' => 11, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'fx_rate' => ['type' => 'decimal', 'length' => 4, 'precision' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'fx_rate_label' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'invoice_number' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'status_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'stage' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'pkid' => ['type' => 'string', 'length' => 32, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'use for the synchronization between dams iris can be deleted when it will disapear', 'precision' => null],
        '_indexes' => [
            'portfolio_id' => ['type' => 'index', 'columns' => ['portfolio_id'], 'length' => []],
            'portfolio_id_2' => ['type' => 'index', 'columns' => ['portfolio_id'], 'length' => []],
            'status_id' => ['type' => 'index', 'columns' => ['status_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['invoice_id'], 'length' => []],
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
                'invoice_id' => 1,
                'portfolio_id' => 1,
                'invoice_owner' => 1,
                'invoice_date' => '2021-03-03',
                'due_date' => '2021-03-03',
                'expected_payment_date' => '2021-03-03',
                'accounting_payment_date' => '2021-03-03',
                'contract_currency' => 'L',
                'amount_curr' => 1.5,
                'amount_eur' => 1.5,
                'fx_rate' => 1.5,
                'fx_rate_label' => 'Lorem ipsum dolor sit amet',
                'invoice_number' => 'Lorem ipsum dolor sit amet',
                'status_id' => 1,
                'stage' => 'Lorem ipsum dolor sit amet',
                'created' => 1614786641,
                'modified' => 1614786641,
                'pkid' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
