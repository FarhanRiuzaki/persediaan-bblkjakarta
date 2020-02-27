<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InternalOrdersDetails Model
 *
 * @property \App\Model\Table\InternalOrdersTable|\Cake\ORM\Association\BelongsTo $InternalOrders
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ExpendituresDistributionsDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresDistributionsDetails
 *
 * @method \App\Model\Entity\InternalOrdersDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\InternalOrdersDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InternalOrdersDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InternalOrdersDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalOrdersDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalOrdersDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InternalOrdersDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InternalOrdersDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InternalOrdersDetailsTable extends Table
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

        $this->setTable('internal_orders_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('InternalOrders', [
            'foreignKey' => 'internal_order_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ExpendituresDistributionsDetails', [
            'foreignKey' => 'internal_orders_detail_id'
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
        $rules->add($rules->existsIn(['internal_order_id'], 'InternalOrders'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
}
