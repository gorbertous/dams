<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GuaranteesFixture
 */
class GuaranteesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'guarantee_id' => ['type' => 'integer', 'length' => null, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'transaction_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sme_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'transaction_reference' => ['type' => 'string', 'length' => 240, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'fiscal_number' => ['type' => 'string', 'length' => 240, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'report_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fi_guarantee_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'fi_guarantee_amount_eur' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'fi_guarantee_amount_curr' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'fi_guarantee_rate' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'fi_guarantee_signature_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'fi_guarantee_maturity_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'subintermediary' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'guarantee_comments' => ['type' => 'string', 'length' => 4000, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'error_message' => ['type' => 'string', 'length' => 1024, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'subintermediary_address' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'subintermediary_postcode' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'subintermediary_place' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'subintermediary_type' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_indexes' => [
            'guarantee_ibfk_1' => ['type' => 'index', 'columns' => ['transaction_id'], 'length' => []],
            'guarantee_ibfk_2' => ['type' => 'index', 'columns' => ['portfolio_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['guarantee_id'], 'length' => []],
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
                'guarantee_id' => 1,
                'transaction_id' => 1,
                'portfolio_id' => 1,
                'sme_id' => 1,
                'transaction_reference' => 'Lorem ipsum dolor sit amet',
                'fiscal_number' => 'Lorem ipsum dolor sit amet',
                'report_id' => 1,
                'fi_guarantee_amount' => 1,
                'fi_guarantee_amount_eur' => 1,
                'fi_guarantee_amount_curr' => 1,
                'fi_guarantee_rate' => 1,
                'fi_guarantee_signature_date' => '2021-03-03',
                'fi_guarantee_maturity_date' => '2021-03-03',
                'subintermediary' => 'Lorem ipsum dolor sit amet',
                'guarantee_comments' => 'Lorem ipsum dolor sit amet',
                'error_message' => 'Lorem ipsum dolor sit amet',
                'subintermediary_address' => 'Lorem ipsum dolor sit amet',
                'subintermediary_postcode' => 'Lorem ipsum dolor sit amet',
                'subintermediary_place' => 'Lorem ipsum dolor sit amet',
                'subintermediary_type' => 'Lorem ipsum dolor sit amet',
                'created' => 1614786593,
                'modified' => 1614786593,
            ],
        ];
        parent::init();
    }
}
