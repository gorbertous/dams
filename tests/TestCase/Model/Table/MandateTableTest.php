<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MandateTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MandateTable Test Case
 */
class MandateTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MandateTable
     */
    protected $Mandate;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Mandate',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Mandate') ? [] : ['className' => MandateTable::class];
        $this->Mandate = $this->getTableLocator()->get('Mandate', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Mandate);

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
