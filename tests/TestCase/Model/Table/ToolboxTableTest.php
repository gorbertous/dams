<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ToolboxTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ToolboxTable Test Case
 */
class ToolboxTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ToolboxTable
     */
    protected $Toolbox;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Toolbox',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Toolbox') ? [] : ['className' => ToolboxTable::class];
        $this->Toolbox = $this->getTableLocator()->get('Toolbox', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Toolbox);

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
}
