<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductUnitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductUnitsTable Test Case
 */
class ProductUnitsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductUnitsTable
     */
    public $ProductUnits;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductUnits',
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
        $config = TableRegistry::getTableLocator()->exists('ProductUnits') ? [] : ['className' => ProductUnitsTable::class];
        $this->ProductUnits = TableRegistry::getTableLocator()->get('ProductUnits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductUnits);

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
