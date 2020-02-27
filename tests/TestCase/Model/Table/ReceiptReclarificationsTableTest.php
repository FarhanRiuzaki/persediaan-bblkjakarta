<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceiptReclarificationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceiptReclarificationsTable Test Case
 */
class ReceiptReclarificationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceiptReclarificationsTable
     */
    public $ReceiptReclarifications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReceiptReclarifications',
        'app.ExpendituresReclarifications',
        'app.ReceiptReclarificationsDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ReceiptReclarifications') ? [] : ['className' => ReceiptReclarificationsTable::class];
        $this->ReceiptReclarifications = TableRegistry::getTableLocator()->get('ReceiptReclarifications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReceiptReclarifications);

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
