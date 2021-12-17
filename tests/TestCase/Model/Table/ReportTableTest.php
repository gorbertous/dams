<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportTable Test Case
 */
class ReportTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportTable
     */
    protected $Report;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Report',
        'app.Portfolios',
        'app.Templates',
        'app.Statuses',
        'app.Invoice',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Report') ? [] : ['className' => ReportTable::class];
        $this->Report = $this->getTableLocator()->get('Report', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Report);

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
