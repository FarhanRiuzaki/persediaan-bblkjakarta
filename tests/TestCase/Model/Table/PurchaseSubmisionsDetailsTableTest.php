<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PurchaseSubmisionsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PurchaseSubmisionsDetailsTable Test Case
 */
class PurchaseSubmisionsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PurchaseSubmisionsDetailsTable
     */
    public $PurchaseSubmisionsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PurchaseSubmisionsDetails',
        'app.PurchaseSubmisions',
        'app.PurchaseRequests',
        'app.PurchaseRequestsDetails',
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
        $config = TableRegistry::getTableLocator()->exists('PurchaseSubmisionsDetails') ? [] : ['className' => PurchaseSubmisionsDetailsTable::class];
        $this->PurchaseSubmisionsDetails = TableRegistry::getTableLocator()->get('PurchaseSubmisionsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PurchaseSubmisionsDetails);

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
