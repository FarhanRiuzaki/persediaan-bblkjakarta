<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UseInstitutesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UseInstitutesTable Test Case
 */
class UseInstitutesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UseInstitutesTable
     */
    public $UseInstitutes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UseInstitutes',
        'app.Institutes',
        'app.Registrations',
        'app.InspectionParameters',
        'app.UseInstitutesDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UseInstitutes') ? [] : ['className' => UseInstitutesTable::class];
        $this->UseInstitutes = TableRegistry::getTableLocator()->get('UseInstitutes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UseInstitutes);

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
