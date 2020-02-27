<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InstitutionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InstitutionsTable Test Case
 */
class InstitutionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InstitutionsTable
     */
    public $Institutions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Institutions',
        'app.ExpendituresTransfers',
        'app.ReceiptTransfers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Institutions') ? [] : ['className' => InstitutionsTable::class];
        $this->Institutions = TableRegistry::getTableLocator()->get('Institutions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Institutions);

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
}
