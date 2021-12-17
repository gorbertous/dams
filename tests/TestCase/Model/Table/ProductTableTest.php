<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductTable Test Case
 */
class ProductTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductTable
     */
    protected $Product;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Product',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Product') ? [] : ['className' => ProductTable::class];
        $this->Product = $this->getTableLocator()->get('Product', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Product);

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
