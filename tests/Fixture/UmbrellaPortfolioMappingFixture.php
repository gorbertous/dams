<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UmbrellaPortfolioMappingFixture
 */
class UmbrellaPortfolioMappingFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'umbrella_portfolio_mapping';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'umbrella_portfolio_mapping_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'umbrella_portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'portfolio_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'umbrella_portfolio_mapping_ibfk_1' => ['type' => 'index', 'columns' => ['umbrella_portfolio_id'], 'length' => []],
            'umbrella_portfolio_mapping_ibfk_2' => ['type' => 'index', 'columns' => ['portfolio_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['umbrella_portfolio_mapping_id'], 'length' => []],
            'umbrella_portfolio_mapping_ibfk_2' => ['type' => 'foreign', 'columns' => ['portfolio_id'], 'references' => ['portfolio', 'portfolio_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'umbrella_portfolio_mapping_ibfk_1' => ['type' => 'foreign', 'columns' => ['umbrella_portfolio_id'], 'references' => ['umbrella_portfolio', 'umbrella_portfolio_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'umbrella_portfolio_mapping_id' => 1,
                'umbrella_portfolio_id' => 1,
                'portfolio_id' => 1,
                'portfolio_name' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
