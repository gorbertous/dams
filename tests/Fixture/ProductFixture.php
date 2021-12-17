<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductFixture
 */
class ProductFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'product';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'product_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 500, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'product_type' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'capped' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => 'YES', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'fixed_rate' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => 'NO', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'reporting_frequency' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['product_id'], 'length' => []],
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
                'product_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'product_type' => 'Lorem ipsum dolor sit amet',
                'capped' => 'Lorem ip',
                'fixed_rate' => 'Lorem ip',
                'reporting_frequency' => 'Lorem ipsum dolor sit amet',
                'created' => '2021-03-03 15:53:38',
                'modified' => 1614786818,
            ],
        ];
        parent::init();
    }
}
