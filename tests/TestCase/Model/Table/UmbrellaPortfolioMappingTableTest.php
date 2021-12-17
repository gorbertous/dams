<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UmbrellaPortfolioMappingTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UmbrellaPortfolioMappingTable Test Case
 */
class UmbrellaPortfolioMappingTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UmbrellaPortfolioMappingTable
     */
    protected $UmbrellaPortfolioMapping;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.UmbrellaPortfolioMapping',
        'app.UmbrellaPortfolio',
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
        $config = $this->getTableLocator()->exists('UmbrellaPortfolioMapping') ? [] : ['className' => UmbrellaPortfolioMappingTable::class];
        $this->UmbrellaPortfolioMapping = $this->getTableLocator()->get('UmbrellaPortfolioMapping', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->UmbrellaPortfolioMapping);

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
