<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * InitStocksDetails Model
 *
 * @property \App\Model\Table\InitStocksTable|\Cake\ORM\Association\BelongsTo $InitStocks
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\InitStocksDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\InitStocksDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InitStocksDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InitStocksDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InitStocksDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InitStocksDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InitStocksDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InitStocksDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InitStocksDetailsTable extends Table
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

        $this->setTable('init_stocks_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('InitStocks', [
            'foreignKey' => 'init_stock_id',
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

        // $validator
        //     ->decimal('qty')
        //     ->requirePresence('qty', 'create')
        //     ->allowEmptyString('qty', false);

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
        $rules->add($rules->existsIn(['init_stock_id'], 'InitStocks'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'init_stocks_details', 'ref_id' => $entity->id]);
        $stocksTable = TableRegistry::get('Stocks');
        $parentTable = TableRegistry::get('InitStocks');
        $parent = $parentTable->get($entity->init_stock_id);
        $stocksTableData = [
            'date' => $parent->date,
            'type' => 'IN',
            'ref_table' => 'init_stocks_details',
            'ref_id' => $entity->id,
            'product_id' => $entity->product_id,
            'qty' =>  (int) $entity->qty,
            'price' => $entity->price
        ];
        
        $stockEntry = $stocksTable->newEntity($stocksTableData);
        // dd($stockEntry);
        $stocksTable->save($stockEntry, ['validate' => false]);
        return true;
    }

    public function afterDelete(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        return TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'init_stocks_details', 'ref_id' => $entity->id]);
    }
}
