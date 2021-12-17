<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UmbrellaPortfolioDeletedTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UmbrellaPortfolioDeletedTable Test Case
 */
class UmbrellaPortfolioDeletedTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UmbrellaPortfolioDeletedTable
     */
    protected $UmbrellaPortfolioDeleted;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.UmbrellaPortfolioDeleted',
        'app.Reports',
        'app.Statuses',
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
        $config = $this->getTableLocator()->exists('UmbrellaPortfolioDeleted') ? [] : ['className' => UmbrellaPortfolioDeletedTable::class];
        $this->UmbrellaPortfolioDeleted = $this->getTableLocator()->get('UmbrellaPortfolioDeleted', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->UmbrellaPortfolioDeleted);

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
