<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PortfolioRatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PortfolioRatesTable Test Case
 */
class PortfolioRatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PortfolioRatesTable
     */
    protected $PortfolioRates;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PortfolioRates',
        'app.Portfolio',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PortfolioRates') ? [] : ['className' => PortfolioRatesTable::class];
        $this->PortfolioRates = $this->getTableLocator()->get('PortfolioRates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PortfolioRates);

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
