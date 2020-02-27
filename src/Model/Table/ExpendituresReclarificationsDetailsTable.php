<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * ExpendituresReclarificationsDetails Model
 *
 * @property \App\Model\Table\ExpendituresReclarificationsTable|\Cake\ORM\Association\BelongsTo $ExpendituresReclarifications
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ReceiptReclarificationsDetailsTable|\Cake\ORM\Association\HasMany $ReceiptReclarificationsDetails
 *
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExpendituresReclarificationsDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExpendituresReclarificationsDetailsTable extends Table
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

        $this->setTable('expenditures_reclarifications_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ExpendituresReclarifications', [
            'foreignKey' => 'expenditures_reclarification_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ReceiptReclarificationsDetails', [
            'foreignKey' => 'expenditures_reclarifications_detail_id'
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
            ->integer('qty')
            ->allowEmptyString('qty');

        $validator
            ->decimal('price')
            ->allowEmptyString('price');

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
        $rules->add($rules->existsIn(['expenditures_reclarification_id'], 'ExpendituresReclarifications'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $stocksTable = TableRegistry::get('Stocks');
        $parentTable = TableRegistry::get('ExpendituresReclarifications');
        $parent = $parentTable->get($entity->expenditures_reclarification_id);

        $stocksTableData = [
            'date' => $parent->date,
            'type' => 'OUT',
            'ref_table' => 'expenditures_reclarifications_details',
            'ref_id' => $entity->id,
            'product_id' => $entity->product_id,
            'qty' => $entity->qty,
            'price' => $entity->price
        ];

        if ($entity->isNew()) {
            $stockEntry = $stocksTable->newEntity($stocksTableData);
        } else {
            $stockEntry = $stocksTable->find('all', [
                'conditions' => [
                    'ref_id' => $entity->id,
                    'ref_table' => 'expenditures_reclarifications_details'
                ]
            ])->first();
            $stockEntry = $stocksTable->patchEntity($stockEntry, $stocksTableData, ['validate' => false]);
        }

        $stocksTable->save($stockEntry, ['validate' => false]);
        return true;
    }

    public function afterDelete(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        return TableRegistry::get('Stocks')->deleteAll(['ref_table' => 'expenditures_reclarifications_details', 'ref_id' => $entity->id]);
    }
}
