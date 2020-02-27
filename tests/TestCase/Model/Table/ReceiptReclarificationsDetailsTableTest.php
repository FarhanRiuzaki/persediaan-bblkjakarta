<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceiptReclarificationsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceiptReclarificationsDetailsTable Test Case
 */
class ReceiptReclarificationsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceiptReclarificationsDetailsTable
     */
    public $ReceiptReclarificationsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReceiptReclarificationsDetails',
        'app.ReceiptReclarifications',
        'app.ExpendituresReclarificationsDetails',
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
        $config = TableRegistry::getTableLocator()->exists('ReceiptReclarificationsDetails') ? [] : ['className' => ReceiptReclarificationsDetailsTable::class];
        $this->ReceiptReclarificationsDetails = TableRegistry::getTableLocator()->get('ReceiptReclarificationsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReceiptReclarificationsDetails);

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
