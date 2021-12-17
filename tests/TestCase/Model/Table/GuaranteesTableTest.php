<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GuaranteesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GuaranteesTable Test Case
 */
class GuaranteesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GuaranteesTable
     */
    protected $Guarantees;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Guarantees',
        'app.Transactions',
        'app.Portfolios',
        'app.Smes',
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
        $config = $this->getTableLocator()->exists('Guarantees') ? [] : ['className' => GuaranteesTable::class];
        $this->Guarantees = $this->getTableLocator()->get('Guarantees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Guarantees);

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
