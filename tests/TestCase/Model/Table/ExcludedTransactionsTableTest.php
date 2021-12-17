<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExcludedTransactionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExcludedTransactionsTable Test Case
 */
class ExcludedTransactionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ExcludedTransactionsTable
     */
    protected $ExcludedTransactions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ExcludedTransactions',
        'app.Smes',
        'app.Transactions',
        'app.Subtransactions',
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
        $config = $this->getTableLocator()->exists('ExcludedTransactions') ? [] : ['className' => ExcludedTransactionsTable::class];
        $this->ExcludedTransactions = $this->getTableLocator()->get('ExcludedTransactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ExcludedTransactions);

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
