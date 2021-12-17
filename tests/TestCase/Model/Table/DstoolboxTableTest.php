<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DstoolboxTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DstoolboxTable Test Case
 */
class DstoolboxTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DstoolboxTable
     */
    protected $Dstoolbox;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Dstoolbox',
        'app.Domains',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Dstoolbox') ? [] : ['className' => DstoolboxTable::class];
        $this->Dstoolbox = $this->getTableLocator()->get('Dstoolbox', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Dstoolbox);

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
