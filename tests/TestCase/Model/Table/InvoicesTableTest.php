<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InvoicesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InvoicesTable Test Case
 */
class InvoicesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InvoicesTable
     */
    protected $Invoice;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Invoice',
        'app.Portfolio',
        'app.Status',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Invoice') ? [] : ['className' => InvoicesTable::class];
        $this->Invoice = $this->getTableLocator()->get('Invoice', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Invoice);

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
