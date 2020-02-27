<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExpendituresReclarificationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExpendituresReclarificationsTable Test Case
 */
class ExpendituresReclarificationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExpendituresReclarificationsTable
     */
    public $ExpendituresReclarifications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExpendituresReclarifications',
        'app.ExpendituresReclarificationsDetails',
        'app.ReceiptReclarifications'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ExpendituresReclarifications') ? [] : ['className' => ExpendituresReclarificationsTable::class];
        $this->ExpendituresReclarifications = TableRegistry::getTableLocator()->get('ExpendituresReclarifications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExpendituresReclarifications);

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
