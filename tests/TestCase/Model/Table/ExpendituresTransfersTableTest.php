<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpendituresTransfersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpendituresTransfersTable Test Case
 */
class ExpendituresTransfersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpendituresTransfersTable
     */
    public $ExpendituresTransfers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExpendituresTransfers',
        'app.Institutions',
        'app.ExpendituresTransfersDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ExpendituresTransfers') ? [] : ['className' => ExpendituresTransfersTable::class];
        $this->ExpendituresTransfers = TableRegistry::getTableLocator()->get('ExpendituresTransfers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpendituresTransfers);

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
