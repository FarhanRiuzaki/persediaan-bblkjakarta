<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceiptOthersDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceiptOthersDetailsTable Test Case
 */
class ReceiptOthersDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceiptOthersDetailsTable
     */
    public $ReceiptOthersDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReceiptOthersDetails',
        'app.ReceiptOthers',
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
        $config = TableRegistry::getTableLocator()->exists('ReceiptOthersDetails') ? [] : ['className' => ReceiptOthersDetailsTable::class];
        $this->ReceiptOthersDetails = TableRegistry::getTableLocator()->get('ReceiptOthersDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReceiptOthersDetails);

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
