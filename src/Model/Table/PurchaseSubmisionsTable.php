<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * PurchaseSubmisions Model
 *
 * @property \App\Model\Table\PurchaseSubmisionsDetailsTable|\Cake\ORM\Association\HasMany $PurchaseSubmisionsDetails
 *
 * @method \App\Model\Entity\PurchaseSubmision get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseSubmision newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmision[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmision|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseSubmision|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseSubmision patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmision[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmision findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PurchaseSubmisionsTable extends Table
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

        $this->setTable('purchase_submisions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('PurchaseSubmisionsDetails', [
            'foreignKey' => 'purchase_submision_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->belongsTo('Institutes', [
            'foreignKey' => 'institute_id',
            'joinType' => 'INNER'
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

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->allowEmptyDate('date', false);

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
    public function beforeSave(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        if ($entity->isNew()) {
            if (!empty($entity->date)) {
                $year = $entity->date->format('Y');
                $month = $entity->date->format('m');
                $formula = 'PS/' . $year . '/' . $month . '/';
                $getLastCode = $this->find('all', [
                    'conditions' => [
                        'YEAR(created)' => $year,
                        'MONTH(created)' => $month
                    ]
                ])
                ->select([
                    'no_urut' => '(CAST(SPLIT_STRING(code, "/", 4) as SIGNED))'
                ])
                ->order('no_urut DESC')
                ->first();
                if (!empty($getLastCode)) {
                    $urut = $getLastCode->no_urut + 1;
                } else {
                    $urut = 1;
                }
                if ($urut <= 10) {
                    $prefix = '000';
                } elseif ($urut <= 100) {
                    $prefix = '00';
                } elseif ($urut <= 1000) {
                    $prefix = '0';
                } elseif ($urut <= 10000) {
                    $prefix = '';
                }
                $entity->code = $formula . $prefix . $urut;
            }
        }

        return true;
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
