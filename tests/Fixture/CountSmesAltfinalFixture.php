<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CountSmesAltfinalFixture
 */
class CountSmesAltfinalFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'count_smes_altfinal';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'period_end_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'total_nbr_of_SMEs' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
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
                'period_end_date' => '2021-03-12',
                'total_nbr_of_SMEs' => 1,
            ],
        ];
        parent::init();
    }
}
