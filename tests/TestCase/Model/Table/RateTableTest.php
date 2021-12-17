<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RateTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RateTable Test Case
 */
class RateTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RateTable
     */
    protected $Rate;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Rate',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Rate') ? [] : ['className' => RateTable::class];
        $this->Rate = $this->getTableLocator()->get('Rate', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Rate);

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
     * Test defaultConnectionName method
     *
     * @return void
     */
    public function testDefaultConnectionName(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
