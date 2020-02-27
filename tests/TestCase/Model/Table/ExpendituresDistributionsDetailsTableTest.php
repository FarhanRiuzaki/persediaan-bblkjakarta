<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpendituresDistributionsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpendituresDistributionsDetailsTable Test Case
 */
class ExpendituresDistributionsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpendituresDistributionsDetailsTable
     */
    public $ExpendituresDistributionsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExpendituresDistributionsDetails',
        'app.ExpendituresDistributions',
        'app.InternalOrders',
        'app.InternalOrdersDetails',
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
        $config = TableRegistry::getTableLocator()->exists('ExpendituresDistributionsDetails') ? [] : ['className' => ExpendituresDistributionsDetailsTable::class];
        $this->ExpendituresDistributionsDetails = TableRegistry::getTableLocator()->get('ExpendituresDistributionsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpendituresDistributionsDetails);

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
