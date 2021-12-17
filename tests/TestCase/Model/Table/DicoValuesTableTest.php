<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DicoValuesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DicoValuesTable Test Case
 */
class DicoValuesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DicoValuesTable
     */
    protected $DicoValues;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.DicoValues',
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
        $config = $this->getTableLocator()->exists('DicoValues') ? [] : ['className' => DicoValuesTable::class];
        $this->DicoValues = $this->getTableLocator()->get('DicoValues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DicoValues);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DicoValuesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\DicoValuesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test defaultConnectionName method
     *
     * @return void
     * @uses \App\Model\Table\DicoValuesTable::defaultConnectionName()
     */
    public function testDefaultConnectionName(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
