<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DictionaryTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DictionaryTable Test Case
 */
class DictionaryTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DictionaryTable
     */
    protected $Dictionary;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Dictionary',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Dictionary') ? [] : ['className' => DictionaryTable::class];
        $this->Dictionary = $this->getTableLocator()->get('Dictionary', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Dictionary);

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
