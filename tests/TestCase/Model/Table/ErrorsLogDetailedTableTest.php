<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ErrorsLogDetailedTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ErrorsLogDetailedTable Test Case
 */
class ErrorsLogDetailedTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ErrorsLogDetailedTable
     */
    protected $ErrorsLogDetailed;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ErrorsLogDetailed',
        'app.ErrorsLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ErrorsLogDetailed') ? [] : ['className' => ErrorsLogDetailedTable::class];
        $this->ErrorsLogDetailed = $this->getTableLocator()->get('ErrorsLogDetailed', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ErrorsLogDetailed);

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
