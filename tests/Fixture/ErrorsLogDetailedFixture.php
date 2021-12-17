<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ErrorsLogDetailedFixture
 */
class ErrorsLogDetailedFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'errors_log_detailed';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'error_detail_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'error_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sheet' => ['type' => 'string', 'length' => 6, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'lines' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'file_formats' => ['type' => 'string', 'length' => 3, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'formats' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'dictionaries' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'integrities' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'business_rules' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'warnings' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_indexes' => [
            'error_detail_id_2' => ['type' => 'index', 'columns' => ['error_detail_id'], 'length' => []],
            'erros_log_detailed_ibfk_1' => ['type' => 'index', 'columns' => ['error_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['error_detail_id'], 'length' => []],
            'error_detail_id' => ['type' => 'unique', 'columns' => ['error_detail_id'], 'length' => []],
            'errors_log_detailed_ibfk_1' => ['type' => 'foreign', 'columns' => ['error_id'], 'references' => ['errors_log', 'error_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'error_detail_id' => 1,
                'error_id' => 1,
                'sheet' => 'Lore',
                'lines' => 1,
                'file_formats' => 'L',
                'formats' => 1,
                'dictionaries' => 1,
                'integrities' => 1,
                'business_rules' => 1,
                'warnings' => 1,
                'created' => '2021-03-03 15:47:45',
                'modified' => 1614786465,
            ],
        ];
        parent::init();
    }
}
