<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExportProducts Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ExportProduct get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExportProduct newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExportProduct[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExportProduct|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExportProduct|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExportProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExportProduct[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExportProduct findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ExportProductsTable extends Table
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

        $this->setTable('export_products');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ExportProductsDetails', [
            'foreignKey' => 'export_product_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->belongsTo('CreatedUsers', [
            'foreignKey' => 'created_by',
            'className'=>'Users'
        ]);
        // $this->belongsTo('Products', [
        //     'foreignKey' => 'product_id',
        //     'joinType' => 'INNER'
        // ]);
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

        // $validator
        //     ->dateTime('date')
        //     ->requirePresence('date', 'create')
        //     ->allowEmptyDateTime('date', false);


        // $validator
        //     ->integer('created_by')
        //     ->requirePresence('created_by', 'create')
        //     ->allowEmptyString('created_by', false);

        // $validator
        //     ->integer('modified_by')
        //     ->requirePresence('modified_by', 'create')
        //     ->allowEmptyString('modified_by', false);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    // public function buildRules(RulesChecker $rules)
    // {
       
    // }
}
