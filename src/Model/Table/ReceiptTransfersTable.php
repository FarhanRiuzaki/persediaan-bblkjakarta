<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReceiptTransfers Model
 *
 * @property \App\Model\Table\InstitutionsTable|\Cake\ORM\Association\BelongsTo $Institutions
 * @property \App\Model\Table\ReceiptTransfersDetailsTable|\Cake\ORM\Association\HasMany $ReceiptTransfersDetails
 *
 * @method \App\Model\Entity\ReceiptTransfer get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReceiptTransfer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptTransfer|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptTransfer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptTransfer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReceiptTransfersTable extends Table
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

        $this->setTable('receipt_transfers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Institutions', [
            'foreignKey' => 'institution_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReceiptTransfersDetails', [
            'foreignKey' => 'receipt_transfer_id',
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['institution_id'], 'Institutions'));

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
                $formula = 'RT/' . $year . '/' . $month . '/';
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
