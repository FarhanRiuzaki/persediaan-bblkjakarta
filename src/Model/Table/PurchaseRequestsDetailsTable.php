<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PurchaseRequestsDetails Model
 *
 * @property \App\Model\Table\PurchaseRequestsTable|\Cake\ORM\Association\BelongsTo $PurchaseRequests
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\PurchaseOrdersDetailsTable|\Cake\ORM\Association\HasMany $PurchaseOrdersDetails
 *
 * @method \App\Model\Entity\PurchaseRequestsDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseRequestsDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseRequestsDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseRequestsDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseRequestsDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseRequestsDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseRequestsDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseRequestsDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PurchaseRequestsDetailsTable extends Table
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

        $this->setTable('purchase_requests_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('PurchaseRequests', [
            'foreignKey' => 'purchase_request_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PurchaseOrdersDetails', [
            'foreignKey' => 'purchase_requests_detail_id'
        ]);
        $this->hasMany('PurchaseSubmisionsDetails', [
            'foreignKey' => 'purchase_requests_detail_id'
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
            ->scalar('spec')
            ->maxLength('spec', 255)
            ->allowEmptyString('spec');

        $validator
            ->scalar('merk')
            ->maxLength('merk', 255)
            ->allowEmptyString('merk');

        $validator
            ->scalar('no_catalog')
            ->maxLength('no_catalog', 50)
            ->allowEmptyString('no_catalog');


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
        $rules->add($rules->existsIn(['purchase_request_id'], 'PurchaseRequests'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
}
