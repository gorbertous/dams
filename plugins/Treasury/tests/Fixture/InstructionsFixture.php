<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InstructionsFixture
 */
class InstructionsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'instr_num' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'instr_type' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => 'DI', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'instr_status' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => 'Created', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'instr_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'notify' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'notified' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'notify_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'mandate_ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cpty_ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created_by' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'validated_by' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'timestamp_validated' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'comment' => ''],
        'validated_file' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'pdf_by' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'timestamp_pdf' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'confirmation_file' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'confirmation_date' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'confirmation_by' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'signedDI_file' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'signedDI_date' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'signedDI_by' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'traderequest_file' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'traderequest_date' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'traderequest_by' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'timestamp_created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['instr_num'], 'length' => []],
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
                'instr_num' => 1,
                'instr_type' => 'Lorem ipsum dolor sit amet',
                'instr_status' => 'Lorem ipsum dolor sit amet',
                'instr_date' => '2021-12-01',
                'notify' => 1,
                'notified' => 1,
                'notify_date' => '2021-12-01',
                'mandate_ID' => 1,
                'cpty_ID' => 1,
                'created_by' => 'Lorem ipsum dolor sit amet',
                'created' => '2021-12-01 17:02:12',
                'validated_by' => 'Lorem ipsum dolor sit amet',
                'timestamp_validated' => 1638374532,
                'validated_file' => 'Lorem ipsum dolor sit amet',
                'pdf_by' => 'Lorem ipsum dolor sit amet',
                'timestamp_pdf' => 1638374532,
                'modified' => '2021-12-01 17:02:12',
                'confirmation_file' => 'Lorem ipsum dolor sit amet',
                'confirmation_date' => '2021-12-01 17:02:12',
                'confirmation_by' => 'Lorem ipsum dolor sit amet',
                'signedDI_file' => 'Lorem ipsum dolor sit amet',
                'signedDI_date' => '2021-12-01 17:02:12',
                'signedDI_by' => 'Lorem ipsum dolor sit amet',
                'traderequest_file' => 'Lorem ipsum dolor sit amet',
                'traderequest_date' => '2021-12-01 17:02:12',
                'traderequest_by' => 'Lorem ipsum dolor sit amet',
                'timestamp_created' => '2021-12-01 17:02:12',
            ],
        ];
        parent::init();
    }
}
