<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ItemReceiptsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ItemReceiptsTable Test Case
 */
class ItemReceiptsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ItemReceiptsTable
     */
    public $ItemReceipts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ItemReceipts',
        'app.ItemReceiptsDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ItemReceipts') ? [] : ['className' => ItemReceiptsTable::class];
        $this->ItemReceipts = TableRegistry::getTableLocator()->get('ItemReceipts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ItemReceipts);

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
