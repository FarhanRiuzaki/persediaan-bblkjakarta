<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ProductsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ProductsController Test Case
 */
class ProductsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Products',
        'app.SubCategories',
        'app.ExpendituresDistributionsDetails',
        'app.ExpendituresGrantsDetails',
        'app.ExpendituresOthersDetails',
        'app.ExpendituresReclarificationsDetails',
        'app.ExpendituresTransfersDetails',
        'app.InitStocksDetails',
        'app.InternalOrdersDetails',
        'app.ProductUnits',
        'app.PurchaseOrdersDetails',
        'app.PurchaseRequestsDetails',
        'app.ReceiptGrantsDetails',
        'app.ReceiptOthersDetails',
        'app.ReceiptPurchasesDetails',
        'app.ReceiptReclarificationsDetails',
        'app.ReceiptTransfersDetails',
        'app.StockInstitutes',
        'app.StockOpnamesDetails',
        'app.Stocks',
        'app.StocksNew',
        'app.UseInstitutesDetails'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
