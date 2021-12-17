<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VUsersTable Test Case
 */
class VUserTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VUsersTable
     */
    protected $VUser;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
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
        $config = $this->getTableLocator()->exists('VUser') ? [] : ['className' => VUsersTable::class];
        $this->VUser = $this->getTableLocator()->get('VUser', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->VUser);

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
