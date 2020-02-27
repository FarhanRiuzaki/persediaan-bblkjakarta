<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Institutes Model
 *
 * @property \App\Model\Table\HeadOfInstitutesTable|\Cake\ORM\Association\BelongsTo $HeadOfInstitutes
 * @property \App\Model\Table\ExpendituresDistributionsTable|\Cake\ORM\Association\HasMany $ExpendituresDistributions
 * @property \App\Model\Table\InspectionParametersTable|\Cake\ORM\Association\HasMany $InspectionParameters
 * @property \App\Model\Table\InternalOrdersTable|\Cake\ORM\Association\HasMany $InternalOrders
 * @property \App\Model\Table\PurchaseRequestsTable|\Cake\ORM\Association\HasMany $PurchaseRequests
 * @property \App\Model\Table\StockInstitutesTable|\Cake\ORM\Association\HasMany $StockInstitutes
 * @property \App\Model\Table\UseInstitutesTable|\Cake\ORM\Association\HasMany $UseInstitutes
 *
 * @method \App\Model\Entity\Institute get($primaryKey, $options = [])
 * @method \App\Model\Entity\Institute newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Institute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Institute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Institute|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Institute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Institute[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Institute findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InstitutesTable extends Table
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

        $this->setTable('institutes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CreatedUsers', [
            'foreignKey' => 'created_by',
            'className' => 'Users'
        ]);

        $this->belongsTo('ModifiedUsers', [
            'foreignKey' => 'modified_by',
            'className' => 'Users'
        ]);
        
        $this->hasMany('ExpendituresDistributions', [
            'foreignKey' => 'institute_id'
        ]);
        $this->hasMany('InspectionParameters', [
            'foreignKey' => 'institute_id'
        ]);
        $this->hasMany('InternalOrders', [
            'foreignKey' => 'institute_id'
        ]);
        $this->hasMany('PurchaseRequests', [
            'foreignKey' => 'institute_id'
        ]);
        $this->hasMany('StockInstitutes', [
            'foreignKey' => 'institute_id'
        ]);
        $this->hasMany('UseInstitutes', [
            'foreignKey' => 'institute_id'
        ]);
        $this->belongsToMany('ExpendituresDistributionsDetails', [
            'through' => 'ExpendituresDistributions',
            'className' => 'Rating',
        ]);

        $this->addBehavior('AuditStash.AuditLog');
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
            ->scalar('name')
            ->maxLength('name', 225)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('head_of_institute')
            ->maxLength('head_of_institute', 225)
            ->requirePresence('head_of_institute', 'create')
            ->allowEmptyString('head_of_institute', false);

        $validator
            ->scalar('position_head_institute')
            ->maxLength('position_head_institute', 225)
            ->requirePresence('position_head_institute', 'create')
            ->allowEmptyString('position_head_institute', false);

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

        $validator
            ->integer('modified_by')
            ->allowEmptyString('modified_by');

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
        return $rules;
    }
}
