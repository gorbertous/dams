<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StatusUmbrellaFixture
 */
class StatusUmbrellaFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'status_umbrella';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'status_id_umbrella' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'stage' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['status_id_umbrella'], 'length' => []],
            'status_key_id' => ['type' => 'foreign', 'columns' => ['status_id_umbrella'], 'references' => ['status', 'status_id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'status_id_umbrella' => 1,
                'stage' => 'Lorem ipsum dolor sit amet',
                'created' => 1620375880,
                'modified' => 1620375880,
            ],
        ];
        parent::init();
    }
}
