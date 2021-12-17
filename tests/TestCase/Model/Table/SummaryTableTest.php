<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SummaryTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SummaryTable Test Case
 */
class SummaryTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SummaryTable
     */
    protected $SummaryTable;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Summary') ? [] : ['className' => SummaryTable::class];
        $this->SummaryTable = $this->getTableLocator()->get('Summary', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SummaryTable);

        parent::tearDown();
    }
}
