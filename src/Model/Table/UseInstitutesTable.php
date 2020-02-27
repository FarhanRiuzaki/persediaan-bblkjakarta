<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UseInstitutes Model
 *
 * @property \App\Model\Table\InstitutesTable|\Cake\ORM\Association\BelongsTo $Institutes
 * @property \App\Model\Table\RegistrationsTable|\Cake\ORM\Association\BelongsTo $Registrations
 * @property \App\Model\Table\InspectionParametersTable|\Cake\ORM\Association\BelongsTo $InspectionParameters
 * @property \App\Model\Table\UseInstitutesDetailsTable|\Cake\ORM\Association\HasMany $UseInstitutesDetails
 *
 * @method \App\Model\Entity\UseInstitute get($primaryKey, $options = [])
 * @method \App\Model\Entity\UseInstitute newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UseInstitute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UseInstitute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UseInstitute|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UseInstitute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UseInstitute[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UseInstitute findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UseInstitutesTable extends Table
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

        $this->setTable('use_institutes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Institutes', [
            'foreignKey' => 'institute_id'
        ]);
        $this->belongsTo('Registrations', [
            'foreignKey' => 'registration_id'
        ]);
        $this->belongsTo('InspectionParameters', [
            'foreignKey' => 'inspection_parameter_id'
        ]);
        
        $this->hasMany('UseInstitutesDetails', [
            'foreignKey' => 'use_institute_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->allowEmptyString('id', 'create');

        $validator
            ->date('date')
            ->allowEmptyDate('date');

        $validator
            ->scalar('type')
            ->allowEmptyString('type');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

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
        $rules->add($rules->existsIn(['registration_id'], 'Registrations'));
        $rules->add($rules->existsIn(['inspection_parameter_id'], 'InspectionParameters'));

        return $rules;
    }

    public function beforeSave(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        if ($entity->isNew()) {
            if (!empty($entity->date)) {
                $year = $entity->date->format('Y');
                $month = $entity->date->format('m');
                $formula = 'UI/' . $year . '/' . $month . '/';
                $getLastCode = $this->find('all', [
                    'conditions' => [
                        'YEAR(date)' => $year,
                        'MONTH(date)' => $month
                    ]
                ])
                ->select([
                    'no_urut' => '(CAST(SPLIT_STRING(code, "/", 4) as SIGNED))'
                ])
                ->order('no_urut DESC')
                ->first();
                if (!empty($getLastCode)) {
                    $urut = $getLastCode->no_urut + 1;
                } else {
                    $urut = 1;
                }
                if ($urut >= 0) {
                    $prefix = '00000';
                } elseif ($urut >= 10) {
                    $prefix = '0000';
                } elseif ($urut >= 100) {
                    $prefix = '000';
                } elseif ($urut >= 1000) {
                    $prefix = '00';
                } elseif ($urut >= 10000) {
                    $prefix = '0';
                } elseif ($urut >= 100000) {
                    $prefix = '';
                }
                $entity->code = $formula . $prefix . $urut;
            }
        }

        return true;
    }
}
