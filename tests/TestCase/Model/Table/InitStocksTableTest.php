<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InitStocksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InitStocksTable Test Case
 */
class InitStocksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InitStocksTable
     */
    public $InitStocks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InitStocks',
        'app.InitStocksDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InitStocks') ? [] : ['className' => InitStocksTable::class];
        $this->InitStocks = TableRegistry::getTableLocator()->get('InitStocks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InitStocks);

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
