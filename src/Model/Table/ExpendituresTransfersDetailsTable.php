<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;


/**
 * ExpendituresTransfersDetails Model
 *
 * @property \App\Model\Table\ExpendituresTransfersTable|\Cake\ORM\Association\BelongsTo $ExpendituresTransfers
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ExpendituresTransfersDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpendituresTransfersDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpendituresTransfersDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresTransfersDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresTransfersDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresTransfersDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresTransfersDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresTransfersDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpendituresTransfersDetailsTable extends Table
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

        $this->setTable('expenditures_transfers_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ExpendituresTransfers', [
            'foreignKey' => 'expenditures_transfer_id',
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
        $rules->add($rules->existsIn(['expenditures_transfer_id'], 'ExpendituresTransfers'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
    

    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $stocksTable = TableRegistry::get('Stocks');
        $parentTable = TableRegistry::get('ExpendituresTransfers');
        $parent = $parentTable->get($entity->expenditures_transfer_id);

        $stocksTableData = [
            'date' => $parent->date,
            'type' => 'OUT',
            'ref_table' => 'expenditures_transfers_details',
            'product_id' => $entity->product_id,
            'ref_id' => $entity->id,
            'qty' => $entity->qty,
            'price' => $entity->price
        ];

        if ($entity->isNew()) {
            $stockEntry = $stocksTable->newEntity($stocksTableData);
        } else {
            $stockEntry = $stocksTable->find('all', [
                'conditions' => [
                    'ref_id' => $entity->id,
                    'ref_table' => 'expenditures_transfers_details'
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
        return TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'expenditures_transfers_details', 'ref_id' => $entity->id]);
    }
}
