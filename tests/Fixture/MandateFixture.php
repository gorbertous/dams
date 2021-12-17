<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MandateFixture
 */
class MandateFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'mandate';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'mandate_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'mandate_iqid' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'mandate_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['mandate_id'], 'length' => []],
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
                'mandate_id' => 1,
                'mandate_iqid' => 'Lorem ipsum dolor sit amet',
                'mandate_name' => 'Lorem ipsum dolor sit amet',
                'created' => '2021-03-03 15:51:06',
                'modified' => 1614786666,
            ],
        ];
        parent::init();
    }
}
