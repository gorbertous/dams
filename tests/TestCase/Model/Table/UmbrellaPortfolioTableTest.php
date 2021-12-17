<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UmbrellaPortfolioTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UmbrellaPortfolioTable Test Case
 */
class UmbrellaPortfolioTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UmbrellaPortfolioTable
     */
    protected $UmbrellaPortfolio;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.UmbrellaPortfolio',
        'app.Product',
        'app.Deleted',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UmbrellaPortfolio') ? [] : ['className' => UmbrellaPortfolioTable::class];
        $this->UmbrellaPortfolio = $this->getTableLocator()->get('UmbrellaPortfolio', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->UmbrellaPortfolio);

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
