<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * StockOpnamesDetails Model
 *
 * @property \App\Model\Table\StockOpnamesTable|\Cake\ORM\Association\BelongsTo $StockOpnames
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\StockOpnamesDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\StockOpnamesDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StockOpnamesDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StockOpnamesDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StockOpnamesDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StockOpnamesDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StockOpnamesDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StockOpnamesDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StockOpnamesDetailsTable extends Table
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

        $this->setTable('stock_opnames_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('StockOpnames', [
            'foreignKey' => 'stock_opname_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id'
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
        //     ->decimal('last_qty')
        //     ->allowEmptyString('last_qty');

        // $validator
        //     ->decimal('qty')
        //     ->requirePresence('qty', 'create')
        //     ->allowEmptyString('qty', false);

        // $validator
        //     ->decimal('qty_diff')
        //     ->requirePresence('qty_diff', 'create')
        //     ->allowEmptyString('qty_diff', false);

        $validator
            ->decimal('price')
            ->allowEmptyString('price');

        $validator
            ->scalar('info')
            ->allowEmptyString('info');

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
        $rules->add($rules->existsIn(['stock_opname_id'], 'StockOpnames'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $stocksTable = TableRegistry::get('Stocks');
        $grantsTable = TableRegistry::get('StockOpnames');
        $parent = $grantsTable->get($entity->stock_opname_id);
        if($entity->qty_diff <= 0){
            $stocksTableData = [
                'date' => $parent->date,
                'type' => 'IN',
                'ref_table' => 'stock_opnames_details',
                'ref_id' => $entity->id,
                'product_id' => $entity->product_id,
                'qty' => ($entity->qty_diff * -1),
                'price' => $entity->price,
            ];
        }else{
            $stocksTableData = [
                'date' => $parent->date,
                'type' => 'OUT',
                'ref_table' => 'stock_opnames_details',
                'ref_id' => $entity->id,
                'product_id' => $entity->product_id,
                'qty' => $entity->qty_diff,
                'price' => $entity->price,
            ];
        }

        if ($entity->isNew()) {
            $stockEntry = $stocksTable->newEntity($stocksTableData);
        } else {
            $stockEntry = $stocksTable->find('all', [
                'conditions' => [
                    'ref_id' => $entity->id,
                    'ref_table' => 'stock_opnames_details'
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
        return TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'stock_opnames_details', 'ref_id' => $entity->id]);
    }
}
