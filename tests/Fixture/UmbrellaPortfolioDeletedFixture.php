<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UmbrellaPortfolioDeletedFixture
 */
class UmbrellaPortfolioDeletedFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'umbrella_portfolio_deleted';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'report_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'report_name' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'status_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'input_filename' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'status_id_umbrella' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'period' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'id_deleted' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'input_filename_umbrella' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id_deleted'], 'length' => []],
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
                'report_id' => 1,
                'report_name' => 'Lorem ipsum dolor sit amet',
                'status_id' => 1,
                'input_filename' => 'Lorem ipsum dolor sit amet',
                'status_id_umbrella' => 1,
                'portfolio_id' => 1,
                'period' => 'Lorem ipsum dolor ',
                'id_deleted' => 1,
                'input_filename_umbrella' => 'Lorem ipsum dolor sit amet',
                'created' => '2021-05-07 15:37:30',
            ],
        ];
        parent::init();
    }
}
