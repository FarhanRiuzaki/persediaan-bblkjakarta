<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InternalOrdersDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InternalOrdersDetailsTable Test Case
 */
class InternalOrdersDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InternalOrdersDetailsTable
     */
    public $InternalOrdersDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InternalOrdersDetails',
        'app.InternalOrders',
        'app.Products',
        'app.ExpendituresDistributionsDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InternalOrdersDetails') ? [] : ['className' => InternalOrdersDetailsTable::class];
        $this->InternalOrdersDetails = TableRegistry::getTableLocator()->get('InternalOrdersDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InternalOrdersDetails);

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
