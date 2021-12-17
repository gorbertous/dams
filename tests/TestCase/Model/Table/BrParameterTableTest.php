<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BrParameterTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BrParameterTable Test Case
 */
class BrParameterTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BrParameterTable
     */
    protected $BrParameter;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.BrParameter',
        'app.TemplateTypes',
        'app.Products',
        'app.Mandates',
        'app.Portfolios',
        'app.Dictionaries',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BrParameter') ? [] : ['className' => BrParameterTable::class];
        $this->BrParameter = $this->getTableLocator()->get('BrParameter', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->BrParameter);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\BrParameterTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\BrParameterTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
