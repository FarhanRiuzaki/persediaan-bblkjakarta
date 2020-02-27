<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * PurchaseSubmisionsDetails Model
 *
 * @property \App\Model\Table\PurchaseSubmisionsTable|\Cake\ORM\Association\BelongsTo $PurchaseSubmisions
 * @property \App\Model\Table\PurchaseRequestsTable|\Cake\ORM\Association\BelongsTo $PurchaseRequests
 * @property \App\Model\Table\PurchaseRequestsDetailsTable|\Cake\ORM\Association\BelongsTo $PurchaseRequestsDetails
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\PurchaseSubmisionsDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseSubmisionsDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmisionsDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmisionsDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseSubmisionsDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseSubmisionsDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmisionsDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseSubmisionsDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PurchaseSubmisionsDetailsTable extends Table
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

        $this->setTable('purchase_submisions_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('PurchaseSubmisions', [
            'foreignKey' => 'purchase_submision_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PurchaseRequests', [
            'foreignKey' => 'purchase_request_id'
        ]);
        $this->belongsTo('PurchaseRequestsDetails', [
            'foreignKey' => 'purchase_requests_detail_id',
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
        $rules->add($rules->existsIn(['purchase_submision_id'], 'PurchaseSubmisions'));
        $rules->add($rules->existsIn(['purchase_request_id'], 'PurchaseRequests'));
        $rules->add($rules->existsIn(['purchase_requests_detail_id'], 'PurchaseRequestsDetails'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $data = $this->PurchaseRequests->updateStatus($entity->purchase_request_id);
        return true;
    }

    public function afterDelete(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $data = $this->PurchaseRequests->updateStatus($entity->purchase_request_id);
        return true;
    }
}
