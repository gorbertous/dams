<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemplateTypeTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemplateTypeTable Test Case
 */
class TemplateTypeTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TemplateTypeTable
     */
    protected $TemplateType;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.TemplateType',
        'app.Rules',
        'app.RulesLogHistory',
        'app.Template',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TemplateType') ? [] : ['className' => TemplateTypeTable::class];
        $this->TemplateType = $this->getTableLocator()->get('TemplateType', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->TemplateType);

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
