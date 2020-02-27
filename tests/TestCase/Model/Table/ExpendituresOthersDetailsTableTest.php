<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpendituresOthersDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpendituresOthersDetailsTable Test Case
 */
class ExpendituresOthersDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpendituresOthersDetailsTable
     */
    public $ExpendituresOthersDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExpendituresOthersDetails',
        'app.ExpendituresOthers',
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
        $config = TableRegistry::getTableLocator()->exists('ExpendituresOthersDetails') ? [] : ['className' => ExpendituresOthersDetailsTable::class];
        $this->ExpendituresOthersDetails = TableRegistry::getTableLocator()->get('ExpendituresOthersDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpendituresOthersDetails);

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
