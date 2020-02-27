<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * ReceiptPurchasesDetails Model
 *
 * @property \App\Model\Table\ReceiptPurchasesTable|\Cake\ORM\Association\BelongsTo $ReceiptPurchases
 * @property \App\Model\Table\PurchaseOrdersDetailsTable|\Cake\ORM\Association\BelongsTo $PurchaseOrdersDetails
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ReceiptPurchasesDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReceiptPurchasesDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReceiptPurchasesDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptPurchasesDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptPurchasesDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptPurchasesDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptPurchasesDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptPurchasesDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReceiptPurchasesDetailsTable extends Table
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

        $this->setTable('receipt_purchases_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReceiptPurchases', [
            'foreignKey' => 'receipt_purchase_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PurchaseOrdersDetails', [
            'foreignKey' => 'purchase_orders_detail_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
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

        $validator
            ->decimal('qty')
            ->requirePresence('qty', 'create')
            ->allowEmptyString('qty', false);

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
        $rules->add($rules->existsIn(['receipt_purchase_id'], 'ReceiptPurchases'));
        $rules->add($rules->existsIn(['purchase_orders_detail_id'], 'PurchaseOrdersDetails'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $stocksTable = TableRegistry::get('Stocks');
        $parentTable = TableRegistry::get('ReceiptPurchases');
        $PurchaseOrdersDetailsTable = TableRegistry::get('PurchaseOrdersDetails');
        $parent = $parentTable->get($entity->receipt_purchase_id);
        $receiptPurchasesDetail = $PurchaseOrdersDetailsTable->get($entity->purchase_orders_detail_id, ['contain' => ['PurchaseOrders']]);

        if ($receiptPurchasesDetail->purchase_order->date_freeze != null) {
            $stocksTableData = [
                'date' => $parent->date,
                'type' => 'IN',
                'ref_table' => 'receipt_purchases_details',
                'ref_id' => $entity->id,
                'product_id' => $entity->product_id,
                'qty' => $entity->qty,
                'price' => $receiptPurchasesDetail->price
            ];

            if ($entity->isNew()) {
                $stockEntry = $stocksTable->newEntity($stocksTableData);
            } else {
                $stockEntry = $stocksTable->find('all', [
                    'conditions' => [
                        'ref_id' => $entity->id,
                        'ref_table' => 'receipt_purchases_details'
                    ]
                ])->first();
                $stockEntry = $stocksTable->patchEntity($stockEntry, $stocksTableData, ['validate' => false]);
            }

            $stocksTable->save($stockEntry, ['validate' => false]);
        }

        return true;
    }

    public function afterDelete(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        return TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'receipt_purchases_details', 'ref_id' => $entity->id]);
    }
}
