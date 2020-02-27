<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PurchaseOrdersDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PurchaseOrdersDetailsTable Test Case
 */
class PurchaseOrdersDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PurchaseOrdersDetailsTable
     */
    public $PurchaseOrdersDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PurchaseOrdersDetails',
        'app.PurchaseOrders',
        'app.PurchaseRequests',
        'app.PurchaseRequestsDetails',
        'app.Products',
        'app.ReceiptPurchasesDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PurchaseOrdersDetails') ? [] : ['className' => PurchaseOrdersDetailsTable::class];
        $this->PurchaseOrdersDetails = TableRegistry::getTableLocator()->get('PurchaseOrdersDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PurchaseOrdersDetails);

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
