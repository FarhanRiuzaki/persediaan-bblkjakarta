<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StockOpnamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StockOpnamesTable Test Case
 */
class StockOpnamesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StockOpnamesTable
     */
    public $StockOpnames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.StockOpnames',
        'app.StockOpnamesDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('StockOpnames') ? [] : ['className' => StockOpnamesTable::class];
        $this->StockOpnames = TableRegistry::getTableLocator()->get('StockOpnames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StockOpnames);

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
