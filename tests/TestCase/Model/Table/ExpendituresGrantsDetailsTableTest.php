<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpendituresGrantsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpendituresGrantsDetailsTable Test Case
 */
class ExpendituresGrantsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpendituresGrantsDetailsTable
     */
    public $ExpendituresGrantsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExpendituresGrantsDetails',
        'app.ExpendituresGrants',
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
        $config = TableRegistry::getTableLocator()->exists('ExpendituresGrantsDetails') ? [] : ['className' => ExpendituresGrantsDetailsTable::class];
        $this->ExpendituresGrantsDetails = TableRegistry::getTableLocator()->get('ExpendituresGrantsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpendituresGrantsDetails);

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
