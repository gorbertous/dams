<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemplateTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemplateTable Test Case
 */
class TemplateTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TemplateTable
     */
    protected $Template;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Template',
        'app.TemplateType',
        'app.Callbacks',
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
        $config = $this->getTableLocator()->exists('Template') ? [] : ['className' => TemplateTable::class];
        $this->Template = $this->getTableLocator()->get('Template', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Template);

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
