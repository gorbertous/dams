<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SmePortfolioTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SmePortfolioTable Test Case
 */
class SmePortfolioTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SmePortfolioTable
     */
    protected $SmePortfolio;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.SmePortfolio',
        'app.Smes',
        'app.Reports',
        'app.Portfolios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SmePortfolio') ? [] : ['className' => SmePortfolioTable::class];
        $this->SmePortfolio = $this->getTableLocator()->get('SmePortfolio', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SmePortfolio);

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
