<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InternalOrders Model
 *
 * @property \App\Model\Table\InstitutesTable|\Cake\ORM\Association\BelongsTo $Institutes
 * @property \App\Model\Table\ExpendituresDistributionsTable|\Cake\ORM\Association\HasMany $ExpendituresDistributions
 * @property \App\Model\Table\ExpendituresDistributionsDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresDistributionsDetails
 * @property \App\Model\Table\InternalOrdersDetailsTable|\Cake\ORM\Association\HasMany $InternalOrdersDetails
 *
 * @method \App\Model\Entity\InternalOrder get($primaryKey, $options = [])
 * @method \App\Model\Entity\InternalOrder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InternalOrder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InternalOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalOrder|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InternalOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InternalOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InternalOrder findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InternalOrdersTable extends Table
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

        $this->setTable('internal_orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Institutes', [
            'foreignKey' => 'institute_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ExpendituresDistributions', [
            'foreignKey' => 'internal_order_id'
        ]);
        $this->hasMany('ExpendituresDistributionsDetails', [
            'foreignKey' => 'internal_order_id'
        ]);
        $this->hasMany('InternalOrdersDetails', [
            'foreignKey' => 'internal_order_id',
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
            ->requirePresence('date', 'create')
            ->allowEmptyDate('date', false);

        $validator
            ->scalar('info')
            ->allowEmptyString('info');

        $validator
            ->allowEmptyString('status');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmptyString('modified_by');

        $validator
            ->dateTime('approve_date')
            ->allowEmptyDateTime('approve_date');

        $validator
            ->integer('approve_by')
            ->allowEmptyString('approve_by');

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
                $formula = 'PU/' . $year . '/' . $month . '/';
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

    public function updateStatus($id)
    {
        $data = $this->get($id, [
            // 'contain' => [
            //     'InternalOrdersDetails' => function ($q) {
            //         return $q->select([
            //             'InternalOrdersDetails__id' => 'InternalOrdersDetails.id',
            //             'InternalOrdersDetails__internal_order_id' => 'InternalOrdersDetails.internal_order_id',
            //             'InternalOrdersDetails__qty' => 'InternalOrdersDetails.qty',
            //             'saldo_dist' => '(SELECT IFNULL(SUM(qty),0) as total_dist FROM expenditures_distributions_details as ExpendituresDistributionsDetails WHERE  ExpendituresDistributionsDetails.internal_orders_detail_id = InternalOrdersDetails.id)'
            //         ]);
            //     }
            // ]
        ]);
        // $status = 0;
        // $status_0 = 0;
        // $status_1 = 0;
        // $status_2 = 0;
        // foreach ($data->internal_orders_details as $key => $r) {
        //     if ($r->saldo_dist >= $r->qty) {
        //         $status_2++;
        //     } elseif ($r->saldo_dist > 0 && $r->saldo_dist < $r->qty) {
        //         $status_1++;
        //     } else {
        //         $status_0++;
        //     }
        // }
        // if ($status_0 == 0 && $status_1 == 0 && $status_2 > 0) {
        //     $status = 4;
        // } elseif ($status_0 == 0 && ($status_1 > 0 || $status_2 > 0)) {
        //     $status = 4;
        // } else {
        //     $status = 4;
        // }
        $status = 4;

        $data = $this->patchEntity($data, ['status' => $status, [
            'validate' => false
        ]]);
        // dd($data);
        $this->save($data);
        return $data;
    }
}
