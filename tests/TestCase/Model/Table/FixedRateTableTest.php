<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FixedRateTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FixedRateTable Test Case
 */
class FixedRateTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FixedRateTable
     */
    protected $FixedRate;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FixedRate',
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
        $config = $this->getTableLocator()->exists('FixedRate') ? [] : ['className' => FixedRateTable::class];
        $this->FixedRate = $this->getTableLocator()->get('FixedRate', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FixedRate);

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
