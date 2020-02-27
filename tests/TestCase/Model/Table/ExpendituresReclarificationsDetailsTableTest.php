<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpendituresReclarificationsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpendituresReclarificationsDetailsTable Test Case
 */
class ExpendituresReclarificationsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpendituresReclarificationsDetailsTable
     */
    public $ExpendituresReclarificationsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExpendituresReclarificationsDetails',
        'app.ExpendituresReclarifications',
        'app.Products',
        'app.ReceiptReclarificationsDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ExpendituresReclarificationsDetails') ? [] : ['className' => ExpendituresReclarificationsDetailsTable::class];
        $this->ExpendituresReclarificationsDetails = TableRegistry::getTableLocator()->get('ExpendituresReclarificationsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpendituresReclarificationsDetails);

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
