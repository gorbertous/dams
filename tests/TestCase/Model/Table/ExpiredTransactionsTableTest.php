<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpiredTransactionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpiredTransactionsTable Test Case
 */
class ExpiredTransactionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpiredTransactionsTable
     */
    protected $ExpiredTransactions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ExpiredTransactions',
        'app.Transactions',
        'app.Subtransactions',
        'app.Smes',
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
        $config = $this->getTableLocator()->exists('ExpiredTransactions') ? [] : ['className' => ExpiredTransactionsTable::class];
        $this->ExpiredTransactions = $this->getTableLocator()->get('ExpiredTransactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ExpiredTransactions);

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
