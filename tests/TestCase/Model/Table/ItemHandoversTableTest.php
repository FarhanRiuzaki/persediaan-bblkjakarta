<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ItemHandoversTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ItemHandoversTable Test Case
 */
class ItemHandoversTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ItemHandoversTable
     */
    public $ItemHandovers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ItemHandovers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ItemHandovers') ? [] : ['className' => ItemHandoversTable::class];
        $this->ItemHandovers = TableRegistry::getTableLocator()->get('ItemHandovers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ItemHandovers);

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
