<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UmbrellaPortfolioFixture
 */
class UmbrellaPortfolioFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'umbrella_portfolio';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'umbrella_portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'umbrella_portfolio_name' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'iqid' => ['type' => 'string', 'length' => 32, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'product_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'splitting_field' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'splitting_table' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'umbrella_portfolio_ibfk_1' => ['type' => 'index', 'columns' => ['product_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['umbrella_portfolio_id'], 'length' => []],
            'umbrella_portfolio_ibfk_1' => ['type' => 'foreign', 'columns' => ['product_id'], 'references' => ['product', 'product_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'umbrella_portfolio_id' => 1,
                'umbrella_portfolio_name' => 'Lorem ipsum dolor sit amet',
                'iqid' => 'Lorem ipsum dolor sit amet',
                'product_id' => 1,
                'splitting_field' => 'Lorem ipsum dolor ',
                'splitting_table' => 'Lorem ipsum dolor ',
            ],
        ];
        parent::init();
    }
}
