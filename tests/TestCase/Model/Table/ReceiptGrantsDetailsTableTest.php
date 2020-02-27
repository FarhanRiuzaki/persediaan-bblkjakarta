<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceiptGrantsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceiptGrantsDetailsTable Test Case
 */
class ReceiptGrantsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceiptGrantsDetailsTable
     */
    public $ReceiptGrantsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReceiptGrantsDetails',
        'app.ReceiptGrants',
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
        $config = TableRegistry::getTableLocator()->exists('ReceiptGrantsDetails') ? [] : ['className' => ReceiptGrantsDetailsTable::class];
        $this->ReceiptGrantsDetails = TableRegistry::getTableLocator()->get('ReceiptGrantsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReceiptGrantsDetails);

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
