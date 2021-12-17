<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DailyTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DailyTable Test Case
 */
class DailyTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DailyTable
     */
    protected $Daily;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Daily',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Daily') ? [] : ['className' => DailyTable::class];
        $this->Daily = $this->getTableLocator()->get('Daily', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Daily);

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
