<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MappingColumnTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MappingColumnTable Test Case
 */
class MappingColumnTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MappingColumnTable
     */
    protected $MappingColumn;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.MappingColumn',
        'app.Tables',
        'app.Dbs',
        'app.Fks',
        'app.Dictionaries',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MappingColumn') ? [] : ['className' => MappingColumnTable::class];
        $this->MappingColumn = $this->getTableLocator()->get('MappingColumn', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MappingColumn);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
