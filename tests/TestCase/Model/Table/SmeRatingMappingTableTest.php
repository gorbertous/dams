<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SmeRatingMappingTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SmeRatingMappingTable Test Case
 */
class SmeRatingMappingTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SmeRatingMappingTable
     */
    protected $SmeRatingMapping;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.SmeRatingMapping',
        'app.Portfolio',
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
        $config = $this->getTableLocator()->exists('SmeRatingMapping') ? [] : ['className' => SmeRatingMappingTable::class];
        $this->SmeRatingMapping = $this->getTableLocator()->get('SmeRatingMapping', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SmeRatingMapping);

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
