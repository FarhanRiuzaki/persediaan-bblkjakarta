<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * ReceiptTransfersDetails Model
 *
 * @property \App\Model\Table\ReceiptTransfersTable|\Cake\ORM\Association\BelongsTo $ReceiptTransfers
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ReceiptTransfersDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReceiptTransfersDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfersDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfersDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptTransfersDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptTransfersDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfersDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfersDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReceiptTransfersDetailsTable extends Table
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

        $this->setTable('receipt_transfers_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ReceiptTransfers', [
            'foreignKey' => 'receipt_transfer_id',
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

        $validator
            ->decimal('price')
            ->allowEmptyString('price');

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
        $rules->add($rules->existsIn(['receipt_transfer_id'], 'ReceiptTransfers'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $stocksTable = TableRegistry::get('Stocks');
        $parentTable = TableRegistry::get('ReceiptTransfers');
        $parent = $parentTable->get($entity->receipt_transfer_id);

        $stocksTableData = [
            'date' => $parent->date,
            'type' => 'IN',
            'ref_table' => 'receipt_transfers_details',
            'ref_id' => $entity->id,
            'product_id' => $entity->product_id,
            'qty' => $entity->qty,
            'price' => $entity->price
        ];

        if ($entity->isNew()) {
            $stockEntry = $stocksTable->newEntity($stocksTableData);
        } else {
            $stockEntry = $stocksTable->find('all', [
                'conditions' => [
                    'ref_id' => $entity->id,
                    'ref_table' => 'receipt_transfers_details'
                ]
            ])->first();
            $stockEntry = $stocksTable->patchEntity($stockEntry, $stocksTableData, ['validate' => false]);
        }

        $stocksTable->save($stockEntry, ['validate' => false]);
        return true;
    }

    public function afterDelete(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        return TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'receipt_transfers_details', 'ref_id' => $entity->id]);
    }
}
