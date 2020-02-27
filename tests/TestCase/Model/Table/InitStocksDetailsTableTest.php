<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InitStocksDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InitStocksDetailsTable Test Case
 */
class InitStocksDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InitStocksDetailsTable
     */
    public $InitStocksDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InitStocksDetails',
        'app.InitStocks',
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
        $config = TableRegistry::getTableLocator()->exists('InitStocksDetails') ? [] : ['className' => InitStocksDetailsTable::class];
        $this->InitStocksDetails = TableRegistry::getTableLocator()->get('InitStocksDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InitStocksDetails);

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
