<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ExcludedTransactionsFixture
 */
class ExcludedTransactionsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'excluded_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'sme_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'transaction_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'subtransaction_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'exclusion_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'excluded_transaction_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'excluded_transaction_amount_eur' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'excluded_transaction_amount_curr' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'exclusion_type' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'coverage_implication' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'acceleration_flag' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'comments' => ['type' => 'string', 'length' => 1000, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'report_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_indexes' => [
            'sme_id' => ['type' => 'index', 'columns' => ['sme_id'], 'length' => []],
            'transaction_id' => ['type' => 'index', 'columns' => ['transaction_id'], 'length' => []],
            'portfolio_id' => ['type' => 'index', 'columns' => ['portfolio_id'], 'length' => []],
            'report_id' => ['type' => 'index', 'columns' => ['report_id'], 'length' => []],
            'portfolio_id_2' => ['type' => 'index', 'columns' => ['portfolio_id', 'report_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['excluded_id'], 'length' => []],
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
                'excluded_id' => 1,
                'sme_id' => 1,
                'transaction_id' => 1,
                'subtransaction_id' => 1,
                'exclusion_date' => '2021-03-03',
                'excluded_transaction_amount' => 1,
                'excluded_transaction_amount_eur' => 1,
                'excluded_transaction_amount_curr' => 1,
                'exclusion_type' => 'Lorem ipsum dolor sit amet',
                'coverage_implication' => 'Lor',
                'acceleration_flag' => 'Lorem ipsum dolor sit amet',
                'comments' => 'Lorem ipsum dolor sit amet',
                'portfolio_id' => 1,
                'report_id' => 1,
                'created' => 1614786484,
                'modified' => 1614786484,
            ],
        ];
        parent::init();
    }
}
