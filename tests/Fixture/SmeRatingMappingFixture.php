<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SmeRatingMappingFixture
 */
class SmeRatingMappingFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'sme_rating_mapping';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'sme_rating_mapping_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'portfolio_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sme_fi_rating_scale' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'sme_rating' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'adjusted_sme_fi_scale' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'adjusted_sme_rating' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'equiv_ori_sme_rating' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_indexes' => [
            'portfolio_id_ibfk_1' => ['type' => 'index', 'columns' => ['portfolio_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['sme_rating_mapping_id'], 'length' => []],
            'portfolio_id_ibfk_1' => ['type' => 'foreign', 'columns' => ['portfolio_id'], 'references' => ['portfolio', 'portfolio_id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'sme_rating_mapping_id' => 1,
                'portfolio_id' => 1,
                'sme_fi_rating_scale' => 'Lorem ipsum dolor sit amet',
                'sme_rating' => 'Lorem ipsum dolor sit amet',
                'adjusted_sme_fi_scale' => 'Lorem ipsum dolor sit amet',
                'adjusted_sme_rating' => 'Lorem ipsum dolor sit amet',
                'equiv_ori_sme_rating' => 'Lorem ipsum dolor sit amet',
                'user_id' => 1,
                'created' => '2021-03-03 15:56:45',
                'modified' => 1614787005,
            ],
        ];
        parent::init();
    }
}
