<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SmeTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SmeTable Test Case
 */
class SmeTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SmeTable
     */
    protected $Sme;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Sme',
        'app.Reports',
        'app.Portfolios',
        'app.Portfolio',
        'app.PortfolioLogHistory',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Sme') ? [] : ['className' => SmeTable::class];
        $this->Sme = $this->getTableLocator()->get('Sme', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Sme);

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
