<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReceiptGrants Model
 *
 * @property \App\Model\Table\ReceiptGrantsDetailsTable|\Cake\ORM\Association\HasMany $ReceiptGrantsDetails
 *
 * @method \App\Model\Entity\ReceiptGrant get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReceiptGrant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReceiptGrant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptGrant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptGrant|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptGrant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptGrant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptGrant findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReceiptGrantsTable extends Table
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

        $this->setTable('receipt_grants');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ReceiptGrantsDetails', [
            'foreignKey' => 'receipt_grant_id',
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
            ->scalar('source')
            ->maxLength('source', 225)
            ->requirePresence('source', 'create')
            ->allowEmptyString('source', false);

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->allowEmptyDate('date', false);

        $validator
            ->date('date_bast')
            ->allowEmptyDate('date_bast');

        $validator
            ->scalar('code_bast')
            ->maxLength('code_bast', 50)
            ->allowEmptyString('code_bast');

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
                    $formula = 'RG/' . $year . '/' . $month . '/';
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
