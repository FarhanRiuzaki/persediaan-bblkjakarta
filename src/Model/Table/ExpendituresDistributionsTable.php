<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * ExpendituresDistributions Model
 *
 * @property \App\Model\Table\InstitutesTable|\Cake\ORM\Association\BelongsTo $Institutes
 * @property \App\Model\Table\InternalOrdersTable|\Cake\ORM\Association\BelongsTo $InternalOrders
 * @property \App\Model\Table\ExpendituresDistributionsDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresDistributionsDetails
 *
 * @method \App\Model\Entity\ExpendituresDistribution get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpendituresDistribution newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpendituresDistribution[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresDistribution|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresDistribution|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresDistribution patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresDistribution[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresDistribution findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpendituresDistributionsTable extends Table
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

        $this->setTable('expenditures_distributions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Institutes', [
            'foreignKey' => 'institute_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InternalOrders', [
            'foreignKey' => 'internal_order_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ExpendituresDistributionsDetails', [
            'foreignKey' => 'expenditures_distribution_id',
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
            ->date('date')
            ->requirePresence('date', 'create')
            ->allowEmptyDate('date', false);

        $validator
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
        $rules->add($rules->existsIn(['internal_order_id'], 'InternalOrders'));

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
                $formula = 'ED/' . $year . '/' . $month . '/';
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
                if ($urut <= 10) {
                    $prefix = '000';
                } elseif ($urut <= 100) {
                    $prefix = '00';
                } elseif ($urut <= 1000) {
                    $prefix = '0';
                } elseif ($urut <= 10000) {
                    $prefix = '';
                }
                $entity->code = $formula . $prefix . $urut;
            }
        }

        return true;
    }

    public function afterDelete(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        TableRegistry::get('InternalOrders')->updateStatus($entity->internal_order_id);
        return true;
    }

    public function afterSave(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        TableRegistry::get('InternalOrders')->updateStatus($entity->internal_order_id);
        return true;
    }
}
