<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PurchaseSubmisionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PurchaseSubmisionsTable Test Case
 */
class PurchaseSubmisionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PurchaseSubmisionsTable
     */
    public $PurchaseSubmisions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PurchaseSubmisions',
        'app.PurchaseSubmisionsDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PurchaseSubmisions') ? [] : ['className' => PurchaseSubmisionsTable::class];
        $this->PurchaseSubmisions = TableRegistry::getTableLocator()->get('PurchaseSubmisions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PurchaseSubmisions);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
