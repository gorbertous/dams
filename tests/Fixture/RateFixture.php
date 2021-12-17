<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RateFixture
 */
class RateFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'rate';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'ID' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'TIME_PERIOD' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'CURRENCY' => ['type' => 'string', 'length' => 3, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'OBS_VALUE' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ID'], 'length' => []],
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
                'ID' => 1,
                'TIME_PERIOD' => '2021-05-11',
                'CURRENCY' => 'L',
                'OBS_VALUE' => 1,
            ],
        ];
        parent::init();
    }
}
