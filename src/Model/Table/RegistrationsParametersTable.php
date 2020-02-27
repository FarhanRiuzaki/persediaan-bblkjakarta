<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RegistrationsParameters Model
 *
 * @property \App\Model\Table\RevesTable|\Cake\ORM\Association\BelongsTo $Reves
 * @property \App\Model\Table\RegistrationsTable|\Cake\ORM\Association\BelongsTo $Registrations
 * @property \App\Model\Table\RegistrationSamplesTable|\Cake\ORM\Association\BelongsTo $RegistrationSamples
 * @property \App\Model\Table\InspectionParametersTable|\Cake\ORM\Association\BelongsTo $InspectionParameters
 * @property \App\Model\Table\InspectionPackagesTable|\Cake\ORM\Association\BelongsTo $InspectionPackages
 *
 * @method \App\Model\Entity\RegistrationsParameter get($primaryKey, $options = [])
 * @method \App\Model\Entity\RegistrationsParameter newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RegistrationsParameter[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RegistrationsParameter|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RegistrationsParameter|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RegistrationsParameter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RegistrationsParameter[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RegistrationsParameter findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RegistrationsParametersTable extends Table
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

        $this->setTable('registrations_parameters');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Reves', [
            'foreignKey' => 'ref_id'
        ]);
        $this->belongsTo('Registrations', [
            'foreignKey' => 'registration_id'
        ]);
        $this->belongsTo('RegistrationSamples', [
            'foreignKey' => 'registration_sample_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InspectionParameters', [
            'foreignKey' => 'inspection_parameter_id'
        ]);
        $this->belongsTo('InspectionPackages', [
            'foreignKey' => 'inspection_package_id'
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
            ->decimal('price')
            ->requirePresence('price', 'create')
            ->allowEmptyString('price', false);

        $validator
            ->integer('qty')
            ->requirePresence('qty', 'create')
            ->allowEmptyString('qty', false);

        $validator
            ->boolean('status')
            ->allowEmptyString('status');

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
        $rules->add($rules->existsIn(['ref_id'], 'Reves'));
        $rules->add($rules->existsIn(['registration_id'], 'Registrations'));
        $rules->add($rules->existsIn(['registration_sample_id'], 'RegistrationSamples'));
        $rules->add($rules->existsIn(['inspection_parameter_id'], 'InspectionParameters'));
        $rules->add($rules->existsIn(['inspection_package_id'], 'InspectionPackages'));

        return $rules;
    }
}
