<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DailyFixture
 */
class DailyFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'daily';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'ORDER' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'DATE' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'CURRENCY' => ['type' => 'string', 'length' => 3, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'OBS_VALUE' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'TREND' => ['type' => 'tinyinteger', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'DIFF' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['CURRENCY'], 'length' => []],
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
                'ORDER' => 1,
                'DATE' => '2021-05-11',
                'CURRENCY' => '190ab2e2-180b-4471-a523-8770416aefa4',
                'OBS_VALUE' => 1,
                'TREND' => 1,
                'DIFF' => 1,
            ],
        ];
        parent::init();
    }
}
