<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PdlrTransactionTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PdlrTransactionTable Test Case
 */
class PdlrTransactionTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PdlrTransactionTable
     */
    protected $PdlrTransaction;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PdlrTransaction',
        'app.ParentPdlrs',
        'app.Smes',
        'app.Transactions',
        'app.Subtransactions',
        'app.Portfolios',
        'app.Reports',
        'app.ParentReports',
        'app.IncludedFrsps',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PdlrTransactions') ? [] : ['className' => PdlrTransactionTable::class];
        $this->PdlrTransactions = $this->getTableLocator()->get('PdlrTransaction', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PdlrTransaction);

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
