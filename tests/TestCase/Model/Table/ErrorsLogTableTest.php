<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ErrorsLogTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ErrorsLogTable Test Case
 */
class ErrorsLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ErrorsLogTable
     */
    protected $ErrorsLog;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ErrorsLog',
        'app.Portfolios',
        'app.Reports',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ErrorsLog') ? [] : ['className' => ErrorsLogTable::class];
        $this->ErrorsLog = $this->getTableLocator()->get('ErrorsLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ErrorsLog);

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
