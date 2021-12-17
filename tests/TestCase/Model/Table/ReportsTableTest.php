<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportsTable Test Case
 */
class ReportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportsTable
     */
    protected $Reports;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Reports',
        'app.Portfolios',
        'app.Loans',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Reports') ? [] : ['className' => ReportsTable::class];
        $this->Reports = $this->getTableLocator()->get('Reports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Reports);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ReportsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ReportsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test defaultConnectionName method
     *
     * @return void
     * @uses \App\Model\Table\ReportsTable::defaultConnectionName()
     */
    public function testDefaultConnectionName(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
