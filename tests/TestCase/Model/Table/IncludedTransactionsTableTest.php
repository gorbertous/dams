<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IncludedTransactionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IncludedTransactionsTable Test Case
 */
class IncludedTransactionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\IncludedTransactionsTable
     */
    protected $IncludedTransactions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.IncludedTransactions',
        'app.Transactions',
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
        $config = $this->getTableLocator()->exists('IncludedTransactions') ? [] : ['className' => IncludedTransactionsTable::class];
        $this->IncludedTransactions = $this->getTableLocator()->get('IncludedTransactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->IncludedTransactions);

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
