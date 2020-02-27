<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InspectionParametersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InspectionParametersTable Test Case
 */
class InspectionParametersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\InspectionParametersTable
     */
    public $InspectionParameters;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InspectionParameters',
        'app.Institutes',
        'app.SubInstitutes',
        'app.RegistrationsParameters',
        'app.UseInstitutes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InspectionParameters') ? [] : ['className' => InspectionParametersTable::class];
        $this->InspectionParameters = TableRegistry::getTableLocator()->get('InspectionParameters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InspectionParameters);

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
