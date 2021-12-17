<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TemplateFixture
 */
class TemplateFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'template';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'template_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'template_type_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'callback_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => null, 'comment' => ''],
        'modified' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        '_indexes' => [
            'template_type_id' => ['type' => 'index', 'columns' => ['template_type_id'], 'length' => []],
            'callback_id' => ['type' => 'index', 'columns' => ['callback_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['template_id'], 'length' => []],
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
                'template_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'template_type_id' => 1,
                'callback_id' => 1,
                'created' => '2021-03-03 15:57:22',
                'modified' => 1614787042,
            ],
        ];
        parent::init();
    }
}
