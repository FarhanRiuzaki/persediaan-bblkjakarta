<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionParameters Model
 *
 * @property \App\Model\Table\InstitutesTable|\Cake\ORM\Association\BelongsTo $Institutes
 * @property \App\Model\Table\SubInstitutesTable|\Cake\ORM\Association\BelongsTo $SubInstitutes
 * @property \App\Model\Table\RegistrationsParametersTable|\Cake\ORM\Association\HasMany $RegistrationsParameters
 * @property \App\Model\Table\UseInstitutesTable|\Cake\ORM\Association\HasMany $UseInstitutes
 *
 * @method \App\Model\Entity\InspectionParameter get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionParameter newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InspectionParameter[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionParameter|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionParameter|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionParameter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionParameter[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionParameter findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InspectionParametersTable extends Table
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

        $this->setTable('inspection_parameters');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Institutes', [
            'foreignKey' => 'institute_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('SubInstitutes', [
            'foreignKey' => 'sub_institute_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('InspectionPackagesDetails', [
            'foreignKey' => 'inspection_parameter_id',
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
            ->requirePresence('id', 'create')
            ->allowEmptyString('id', false);

        $validator
            ->scalar('name')
            ->maxLength('name', 225)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('method')
            ->maxLength('method', 225)
            ->allowEmptyString('method');

        $validator
            ->decimal('price')
            ->requirePresence('price', 'create')
            ->allowEmptyString('price', false);

        $validator
            ->scalar('unit')
            ->maxLength('unit', 50)
            ->allowEmptyString('unit');

        $validator
            ->decimal('duration')
            ->allowEmptyString('duration');

        $validator
            ->boolean('status')
            ->allowEmptyString('status');

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
        $rules->add($rules->existsIn(['institute_id'], 'Institutes'));
        // $rules->add($rules->existsIn(['sub_institute_id'], 'SubInstitutes'));

        return $rules;
    }
}
