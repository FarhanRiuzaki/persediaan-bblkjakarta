<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InternalOrdersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InternalOrdersTable Test Case
 */
class InternalOrdersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InternalOrdersTable
     */
    public $InternalOrders;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InternalOrders',
        'app.Institutes',
        'app.ExpendituresDistributions',
        'app.ExpendituresDistributionsDetails',
        'app.InternalOrdersDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InternalOrders') ? [] : ['className' => InternalOrdersTable::class];
        $this->InternalOrders = TableRegistry::getTableLocator()->get('InternalOrders', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InternalOrders);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
