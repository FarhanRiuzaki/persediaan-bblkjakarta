<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \App\Model\Table\SubCategoriesTable|\Cake\ORM\Association\BelongsTo $SubCategories
 * @property \App\Model\Table\ExpendituresDistributionsDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresDistributionsDetails
 * @property \App\Model\Table\ExpendituresGrantsDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresGrantsDetails
 * @property \App\Model\Table\ExpendituresOthersDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresOthersDetails
 * @property \App\Model\Table\ExpendituresReclarificationsDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresReclarificationsDetails
 * @property \App\Model\Table\ExpendituresTransfersDetailsTable|\Cake\ORM\Association\HasMany $ExpendituresTransfersDetails
 * @property \App\Model\Table\InitStocksDetailsTable|\Cake\ORM\Association\HasMany $InitStocksDetails
 * @property \App\Model\Table\InternalOrdersDetailsTable|\Cake\ORM\Association\HasMany $InternalOrdersDetails
 * @property \App\Model\Table\ProductUnitsTable|\Cake\ORM\Association\HasMany $ProductUnits
 * @property \App\Model\Table\PurchaseOrdersDetailsTable|\Cake\ORM\Association\HasMany $PurchaseOrdersDetails
 * @property \App\Model\Table\PurchaseRequestsDetailsTable|\Cake\ORM\Association\HasMany $PurchaseRequestsDetails
 * @property \App\Model\Table\ReceiptGrantsDetailsTable|\Cake\ORM\Association\HasMany $ReceiptGrantsDetails
 * @property \App\Model\Table\ReceiptOthersDetailsTable|\Cake\ORM\Association\HasMany $ReceiptOthersDetails
 * @property \App\Model\Table\ReceiptPurchasesDetailsTable|\Cake\ORM\Association\HasMany $ReceiptPurchasesDetails
 * @property \App\Model\Table\ReceiptReclarificationsDetailsTable|\Cake\ORM\Association\HasMany $ReceiptReclarificationsDetails
 * @property \App\Model\Table\ReceiptTransfersDetailsTable|\Cake\ORM\Association\HasMany $ReceiptTransfersDetails
 * @property \App\Model\Table\StockInstitutesTable|\Cake\ORM\Association\HasMany $StockInstitutes
 * @property \App\Model\Table\StockOpnamesDetailsTable|\Cake\ORM\Association\HasMany $StockOpnamesDetails
 * @property \App\Model\Table\StocksTable|\Cake\ORM\Association\HasMany $Stocks
 * @property \App\Model\Table\StocksNewTable|\Cake\ORM\Association\HasMany $StocksNew
 * @property \App\Model\Table\UseInstitutesDetailsTable|\Cake\ORM\Association\HasMany $UseInstitutesDetails
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductsTable extends Table
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

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('CreatedUsers', [
            'foreignKey' => 'created_by',
            'className' => 'Users'
        ]);

        $this->belongsTo('ModifiedUsers', [
            'foreignKey' => 'modified_by',
            'className' => 'Users'
        ]);

        $this->belongsTo('SubCategories', [
            'foreignKey' => 'sub_category_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Stocks', [
            'foreignKey' => 'id',
            'joinType' => 'LEFT'
        ]);

        $this->hasOne('ProductUnits');
        $this->addBehavior('AuditStash.AuditLog');
        $this->addBehavior('Timestamp');
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
            ->scalar('code')
            ->maxLength('code', 225)
            ->allowEmptyString('code');

        $validator
            ->scalar('name')
            ->maxLength('name', 225)
            ->allowEmptyString('name');

        $validator
            ->scalar('unit')
            ->maxLength('unit', 225)
            ->allowEmptyString('unit');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

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
        $rules->add($rules->existsIn(['sub_category_id'], 'SubCategories'));

        return $rules;
    }

    public function beforeSave(
        \Cake\Event\Event $event,
        \Cake\ORM\Entity $entity,
    \ArrayObject $options
    ) {
        // $this->loadModel('SubCategories');
        $kode = $this->SubCategories->get($entity->sub_category_id, [
            'contain' => [
                'Categories'
            ]
        ]);
        // dd($kode);
        $kode = $kode->category->code;
        if ($entity->isNew()) {
            $year = date('Y');
            $month = date('m');

            // $kode = 
            $formula = $kode . '/' . $year . '/' . $month . '/';
            
            $getLastCode = $this->find('all')
            ->where(["SUBSTRING_INDEX(`code`, '/', 1) = '". $kode ."'"])
            ->select([
                'no_urut' => '(CAST(SPLIT_STRING(code, "/", 4) as SIGNED))'
            ])
            ->order('no_urut DESC')
            ->first();
            // dd($getLastCode);
            
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

        return true;
    }
}
