<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * UseInstitutesDetails Model
 *
 * @property \App\Model\Table\UseInstitutesTable|\Cake\ORM\Association\BelongsTo $UseInstitutes
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\UseInstitutesDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\UseInstitutesDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UseInstitutesDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UseInstitutesDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UseInstitutesDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UseInstitutesDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UseInstitutesDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UseInstitutesDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UseInstitutesDetailsTable extends Table
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

        $this->setTable('use_institutes_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('UseInstitutes', [
            'foreignKey' => 'use_institute_id'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id'
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
            ->decimal('qty')
            ->allowEmptyString('qty');

        $validator
            ->scalar('unit')
            ->maxLength('unit', 255)
            ->allowEmptyString('unit');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options)
    {
        $stocksTable = TableRegistry::get('StockInstitutes');
        $parentTable = TableRegistry::get('UseInstitutes');
        $parent = $parentTable->get($entity->use_institute_id);

        $stocksTableData = [
            'institute_id' => $parent->institute_id,
            'date' => $parent->date,
            'type' => 'IN',
            'ref_table' => 'use_institutes_details',
            'ref_id' => $entity->id,
            'product_id' => $entity->product_id,
            'qty' => $entity->qty,
            'unit' => $entity->unit,
            'price' => $entity->price
        ];

        $stockEntry = $stocksTable->newEntity($stocksTableData);
        $stocksTable->save($stockEntry, ['validate' => false]);

        return true;
    }

    public function afterDelete(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        return TableRegistry::get('StockInstitutes')->deleteAll(['ref_table' => 'use_institutes_details', 'ref_id' => $entity->id]);
    }
}
