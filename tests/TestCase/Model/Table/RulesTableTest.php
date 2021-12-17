<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RulesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RulesTable Test Case
 */
class RulesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RulesTable
     */
    protected $Rules;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Rules',
        'app.Products',
        'app.Mandates',
        'app.Portfolios',
        'app.Trueparts',
        'app.Falseparts',
        'app.TemplateType',
        'app.VUser',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Rules') ? [] : ['className' => RulesTable::class];
        $this->Rules = $this->getTableLocator()->get('Rules', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Rules);

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
