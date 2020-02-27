<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RegistrationsParametersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RegistrationsParametersTable Test Case
 */
class RegistrationsParametersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RegistrationsParametersTable
     */
    public $RegistrationsParameters;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RegistrationsParameters',
        'app.Reves',
        'app.Registrations',
        'app.RegistrationSamples',
        'app.InspectionParameters',
        'app.InspectionPackages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RegistrationsParameters') ? [] : ['className' => RegistrationsParametersTable::class];
        $this->RegistrationsParameters = TableRegistry::getTableLocator()->get('RegistrationsParameters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RegistrationsParameters);

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
