<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExpendituresOthers Model
 *
 * @property \App\Model\Table\ExpendituresOthersDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresOthersDetails
 *
 * @method \App\Model\Entity\ExpendituresOther get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpendituresOther newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpendituresOther[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresOther|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresOther|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresOther patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresOther[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresOther findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpendituresOthersTable extends Table
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

        $this->setTable('expenditures_others');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ExpendituresOthersDetails', [
            'foreignKey' => 'expenditures_other_id',
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
            ->scalar('given_to')
            ->maxLength('given_to', 225)
            ->requirePresence('given_to', 'create')
            ->allowEmptyString('given_to', false);

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->allowEmptyDate('date', false);

        $validator
            ->boolean('status')
            ->allowEmptyString('status');

        $validator
            ->integer('modified_by')
            ->allowEmptyString('modified_by');

        return $validator;
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
                $formula = 'EO/' . $year . '/' . $month . '/';
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
