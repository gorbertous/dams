<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PortfolioTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PortfolioTable Test Case
 */
class PortfolioTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PortfolioTable
     */
    protected $Portfolio;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Portfolio',
        'app.Products',
        'app.Sme',
        'app.Template',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Portfolio') ? [] : ['className' => PortfolioTable::class];
        $this->Portfolio = $this->getTableLocator()->get('Portfolio', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Portfolio);

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
