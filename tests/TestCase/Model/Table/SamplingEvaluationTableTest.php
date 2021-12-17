<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SamplingEvaluationTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SamplingEvaluationTable Test Case
 */
class SamplingEvaluationTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SamplingEvaluationTable
     */
    protected $SamplingEvaluation;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.SamplingEvaluation',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SamplingEvaluation') ? [] : ['className' => SamplingEvaluationTable::class];
        $this->SamplingEvaluation = $this->getTableLocator()->get('SamplingEvaluation', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SamplingEvaluation);

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
