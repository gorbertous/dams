<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnnualSamplingParametersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnnualSamplingParametersTable Test Case
 */
class AnnualSamplingParametersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AnnualSamplingParametersTable
     */
    protected $AnnualSamplingParameters;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.AnnualSamplingParameters',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AnnualSamplingParameters') ? [] : ['className' => AnnualSamplingParametersTable::class];
        $this->AnnualSamplingParameters = $this->getTableLocator()->get('AnnualSamplingParameters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AnnualSamplingParameters);

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
