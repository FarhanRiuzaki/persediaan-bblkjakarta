<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * PurchaseOrders Model
 *
 * @property \App\Model\Table\SuppliersTable|\Cake\ORM\Association\BelongsTo $Suppliers
 * @property \App\Model\Table\PurchaseOrdersDetailsTable|\Cake\ORM\Association\HasMany $PurchaseOrdersDetails
 * @property \App\Model\Table\ReceiptPurchasesTable|\Cake\ORM\Association\HasMany $ReceiptPurchases
 *
 * @method \App\Model\Entity\PurchaseOrder get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseOrder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseOrder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseOrder|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseOrder findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PurchaseOrdersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('purchase_orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Suppliers', [
            'foreignKey' => 'supplier_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('PurchaseOrdersDetails', [
            'foreignKey' => 'purchase_order_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('PurchaseRequestsDetails', [
            'foreignKey' => 'purchase_request_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->hasMany('ReceiptPurchases', [
            'foreignKey' => 'purchase_order_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);

        $this->belongsTo('CreatedUsers', [
            'foreignKey' => 'created_by',
            'className' => 'Users'
        ]);
        $this->belongsTo('ModifiedUsers', [
            'foreignKey' => 'modified_by',
            'className' => 'Users'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        // $validator
        //     ->scalar('nomor_spk')
        //     ->maxLength('nomor_spk', 225)
        //     ->requirePresence('nomor_spk', 'create')
        //     ->allowEmptyString('nomor_spk', false);

        // $validator
        //     ->date('date')
        //     ->requirePresence('date', 'create')
        //     ->allowEmptyDate('date', false);

        $validator
            ->date('date_freeze')
            ->allowEmptyDate('date_freeze');

        $validator
            ->boolean('status')
            ->allowEmptyString('status');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmptyString('modified_by');

        $validator
            ->dateTime('approve_date')
            ->allowEmptyDateTime('approve_date');

        $validator
            ->integer('approve_by')
            ->allowEmptyString('approve_by');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['supplier_id'], 'Suppliers'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $purchase_order_id = $entity->id;
        $receiptPurchasesDetailTable = TableRegistry::get('ReceiptPurchasesDetails');
        $receiptPurchasesDetailData = $receiptPurchasesDetailTable->find('all', [
            'conditions' => [
                'ReceiptPurchases.purchase_order_id' => $purchase_order_id
            ],
            'contain' => ['ReceiptPurchases', 'PurchaseOrdersDetails']
        ]);

        // if ($entity->date_freeze != null) {
        //     foreach ($receiptPurchasesDetailData as $key => $value) {
        //         $stocksTable = TableRegistry::get('Stocks');

        //         $stocksTableData = [
        //             'date' => $entity->date_freeze,
        //             'type' => 'IN',
        //             'ref_table' => 'receipt_purchases_details',
        //             'ref_id' => $value->id,
        //             'product_id' => $value->product_id,
        //             'qty' => $value->qty,
        //             'price' => $value->purchase_orders_detail->price
        //         ];

        //         $stockEntry = $stocksTable->find('all', [
        //             'conditions' => [
        //                 'ref_id' => $value->id,
        //                 'ref_table' => 'receipt_purchases_details'
        //             ]
        //         ])->first();

        //         if ($stockEntry) {
        //             $stockEntry = $stocksTable->patchEntity($stockEntry, $stocksTableData, ['validate' => false]);
        //         } else {
        //             $stockEntry = $stocksTable->newEntity($stocksTableData);
        //         }

        //         $stocksTable->save($stockEntry, ['validate' => false]);
        //     }
        // } else {
        //     foreach ($receiptPurchasesDetailData as $key => $value) {
        //         TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'receipt_purchases_details', 'ref_id' => $value->id]);
        //     }
        // }
    }
}
