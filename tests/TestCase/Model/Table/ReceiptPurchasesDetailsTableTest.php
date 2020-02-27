<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceiptPurchasesDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceiptPurchasesDetailsTable Test Case
 */
class ReceiptPurchasesDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceiptPurchasesDetailsTable
     */
    public $ReceiptPurchasesDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReceiptPurchasesDetails',
        'app.ReceiptPurchases',
        'app.PurchaseOrdersDetails',
        'app.Products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ReceiptPurchasesDetails') ? [] : ['className' => ReceiptPurchasesDetailsTable::class];
        $this->ReceiptPurchasesDetails = TableRegistry::getTableLocator()->get('ReceiptPurchasesDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReceiptPurchasesDetails);

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
