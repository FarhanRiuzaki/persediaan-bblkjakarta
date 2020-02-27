<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceiptOthersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceiptOthersTable Test Case
 */
class ReceiptOthersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceiptOthersTable
     */
    public $ReceiptOthers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReceiptOthers',
        'app.ReceiptOthersDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ReceiptOthers') ? [] : ['className' => ReceiptOthersTable::class];
        $this->ReceiptOthers = TableRegistry::getTableLocator()->get('ReceiptOthers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReceiptOthers);

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
