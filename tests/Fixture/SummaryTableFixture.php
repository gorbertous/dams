<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SummaryTableFixture
 */
class SummaryTableFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'summary_table';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'mandate' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'sum_princ_guar_amount_eur' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'sum_disb_guar_amount_eur' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'number_of_loans' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'number_of_SMEs' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
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
                'mandate' => 'Lorem ipsum dolor sit amet',
                'sum_princ_guar_amount_eur' => 1,
                'sum_disb_guar_amount_eur' => 1,
                'number_of_loans' => 1,
                'number_of_SMEs' => 1,
            ],
        ];
        parent::init();
    }
}
