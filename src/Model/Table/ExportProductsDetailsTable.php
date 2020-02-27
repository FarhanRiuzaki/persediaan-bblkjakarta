<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExportProductsDetails Model
 *
 * @property \App\Model\Table\ExportProductsTable|\Cake\ORM\Association\BelongsTo $ExportProducts
 *
 * @method \App\Model\Entity\ExportProductsDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExportProductsDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExportProductsDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExportProductsDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExportProductsDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExportProductsDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExportProductsDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExportProductsDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class ExportProductsDetailsTable extends Table
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

        $this->setTable('export_products_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ExportProducts', [
            'foreignKey' => 'export_product_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
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
            ->integer('qty')
            ->allowEmptyString('qty');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

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
        $rules->add($rules->existsIn(['export_product_id'], 'ExportProducts'));

        return $rules;
    }
}
