<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TransactionsFixture
 */
class TransactionsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'tr_number' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'tr_type' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'tr_state' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'source_group' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'reinv_group' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'original_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parent_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'linked_trn' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'external_ref' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'amount' => ['type' => 'decimal', 'length' => 15, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'commencement_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'maturity_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'indicative_maturity_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'depo_term' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'interest_rate' => ['type' => 'decimal', 'length' => 11, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'total_interest' => ['type' => 'decimal', 'length' => 15, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'tax_amount' => ['type' => 'decimal', 'length' => 15, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'depo_type' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'depo_renew' => ['type' => 'string', 'length' => 3, 'null' => true, 'default' => 'No', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'rate_type' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'date_basis' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'mandate_ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cmp_ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'scheme' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'accountA_IBAN' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'accountB_IBAN' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'instr_num' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cpty_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ps_account' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'booking_status' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => 'Not booked', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'eom_booking' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => 'Not booked', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'accrued_interst' => ['type' => 'decimal', 'length' => 15, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'accrued_tax' => ['type' => 'decimal', 'length' => 15, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'fixing_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'eom_interest' => ['type' => 'decimal', 'length' => 15, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'eom_tax' => ['type' => 'decimal', 'length' => 15, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'tax_ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'source_fund' => ['type' => 'char', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'comment' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'reference_rate' => ['type' => 'decimal', 'length' => 11, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'spread_bp' => ['type' => 'decimal', 'length' => 11, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'benchmark' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => '0000-00-00 00:00:00', 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['tr_number'], 'length' => []],
        ],
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
                'tr_number' => 1,
                'tr_type' => 'Lorem ipsum dolor ',
                'tr_state' => 'Lorem ipsum dolor sit a',
                'source_group' => 1,
                'reinv_group' => 1,
                'original_id' => 1,
                'parent_id' => 1,
                'linked_trn' => 1,
                'external_ref' => 'Lorem ipsum dolor sit amet',
                'amount' => 1.5,
                'commencement_date' => '2021-12-01',
                'maturity_date' => '2021-12-01',
                'indicative_maturity_date' => '2021-12-01',
                'depo_term' => 'Lo',
                'interest_rate' => 1.5,
                'total_interest' => 1.5,
                'tax_amount' => 1.5,
                'depo_type' => 'Lorem ip',
                'depo_renew' => 'L',
                'rate_type' => 'Lorem ip',
                'date_basis' => 'Lorem ip',
                'mandate_ID' => 1,
                'cmp_ID' => 1,
                'scheme' => 'Lo',
                'accountA_IBAN' => 'Lorem ipsum dolor sit amet',
                'accountB_IBAN' => 'Lorem ipsum dolor sit amet',
                'instr_num' => 1,
                'cpty_id' => 1,
                'ps_account' => 'Lorem ipsum dolor sit amet',
                'booking_status' => 'Lorem ip',
                'eom_booking' => 'Lorem ipsum dolor sit amet',
                'accrued_interst' => 1.5,
                'accrued_tax' => 1.5,
                'fixing_date' => '2021-12-01',
                'eom_interest' => 1.5,
                'eom_tax' => 1.5,
                'tax_ID' => 1,
                'source_fund' => '',
                'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'reference_rate' => 1.5,
                'spread_bp' => 1.5,
                'benchmark' => 'Lorem ipsum dolor sit amet',
                'created' => '2021-12-01 17:09:30',
                'modified' => '2021-12-01 17:09:30',
            ],
        ];
        parent::init();
    }
}
