<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InstitutesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InstitutesTable Test Case
 */
class InstitutesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InstitutesTable
     */
    public $Institutes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Institutes',
        'app.HeadOfInstitutes',
        'app.ExpendituresDistributions',
        'app.InspectionParameters',
        'app.InternalOrders',
        'app.PurchaseRequests',
        'app.StockInstitutes',
        'app.UseInstitutes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Institutes') ? [] : ['className' => InstitutesTable::class];
        $this->Institutes = TableRegistry::getTableLocator()->get('Institutes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Institutes);

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
