<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PurchaseRequestsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PurchaseRequestsDetailsTable Test Case
 */
class PurchaseRequestsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PurchaseRequestsDetailsTable
     */
    public $PurchaseRequestsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PurchaseRequestsDetails',
        'app.PurchaseRequests',
        'app.Products',
        'app.PurchaseOrdersDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PurchaseRequestsDetails') ? [] : ['className' => PurchaseRequestsDetailsTable::class];
        $this->PurchaseRequestsDetails = TableRegistry::getTableLocator()->get('PurchaseRequestsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PurchaseRequestsDetails);

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
