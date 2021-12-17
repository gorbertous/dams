<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CountSmesAltfinalTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CountSmesAltfinalTable Test Case
 */
class CountSmesAltfinalTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CountSmesAltfinalTable
     */
    protected $CountSmesAltfinal;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.CountSmesAltfinal',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CountSmesAltfinal') ? [] : ['className' => CountSmesAltfinalTable::class];
        $this->CountSmesAltfinal = $this->getTableLocator()->get('CountSmesAltfinal', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->CountSmesAltfinal);

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
