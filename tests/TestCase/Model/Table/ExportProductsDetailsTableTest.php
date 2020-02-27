<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExportProductsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExportProductsDetailsTable Test Case
 */
class ExportProductsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExportProductsDetailsTable
     */
    public $ExportProductsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExportProductsDetails',
        'app.ExportProducts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ExportProductsDetails') ? [] : ['className' => ExportProductsDetailsTable::class];
        $this->ExportProductsDetails = TableRegistry::getTableLocator()->get('ExportProductsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExportProductsDetails);

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
