<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PurchaseRequests Model
 *
 * @property \App\Model\Table\InstitutesTable|\Cake\ORM\Association\BelongsTo $Institutes
 * @property \App\Model\Table\PurchaseOrdersDetailsTable|\Cake\ORM\Association\HasMany $PurchaseOrdersDetails
 * @property \App\Model\Table\PurchaseRequestsDetailsTable|\Cake\ORM\Association\HasMany $PurchaseRequestsDetails
 *
 * @method \App\Model\Entity\PurchaseRequest get($primaryKey, $options = [])
 * @method \App\Model\Entity\PurchaseRequest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PurchaseRequest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseRequest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseRequest|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PurchaseRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseRequest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PurchaseRequest findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PurchaseRequestsTable extends Table
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

        $this->setTable('purchase_requests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Institutes', [
            'foreignKey' => 'institute_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PurchaseOrdersDetails', [
            'foreignKey' => 'purchase_request_id'
        ]);
        $this->hasMany('PurchaseSubmisionsDetails', [
            'foreignKey' => 'purchase_request_id'
        ]);
        $this->hasMany('PurchaseRequestsDetails', [
            'foreignKey' => 'purchase_request_id',
            'dependent' => true,
            'cascadeCallbacks' => true
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
                $formula = 'PP/' . $year . '/' . $month . '/';
                $getLastCode = $this->find('all', [
                    'conditions' => [
                        'YEAR(created)' => $year,
                        'MONTH(created)' => $month
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
            'contain' => [
                'PurchaseRequestsDetails' => function ($q) {
                    return $q->select([
                        'PurchaseRequestsDetails__id' => 'PurchaseRequestsDetails.id',
                        'PurchaseRequestsDetails__purchase_request_id' => 'PurchaseRequestsDetails.purchase_request_id',
                        'PurchaseRequestsDetails__qty' => 'PurchaseRequestsDetails.qty',
                        'saldo_po' => '(SELECT IFNULL(SUM(qty),0) as total_dist FROM purchase_submisions_details as PurchaseSubmisionsDetails WHERE  PurchaseSubmisionsDetails.purchase_requests_detail_id = PurchaseRequestsDetails.id)'
                    ]);
                }
            ]
        ]);
        // $status = 0;
        // $status_0 = 0;
        // $status_1 = 0;
        // $status_2 = 0;
        // foreach ($data->purchase_requests_details as $key => $r) {
        //     if ($r->saldo_po >= $r->qty) {
        //         $status_2++;
        //     } elseif ($r->saldo_po > 0 && $r->saldo_po < $r->qty) {
        //         $status_1++;
        //     } else {
        //         $status_0++;
        //     }
        // }
        // if ($status_0 == 0 && $status_1 == 0 && $status_2 > 0) {
        //     $status = 2;
        // } elseif ($status_0 == 0 && ($status_1 > 0 || $status_2 > 0)) {
        //     $status = 1;
        // } else {
        //     $status = 0;
        // }
        $status = 1;
        $data = $this->patchEntity($data, ['status' => $status, [
            'validate' => false
        ]]);
        // dd($data);
        $this->save($data);
        return $data;
    }
}
