<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExportProduct Entity
 *
 * @property int $id
 * @property int $product_id
 * @property \Cake\I18n\FrozenTime $date
 * @property int $status
 * @property \Cake\I18n\FrozenDate $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenDate $modified
 * @property int $modified_by
 *
 * @property \App\Model\Entity\Product $product
 */
class ExportProduct extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'date' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'product' => true,
        'export_products_details' => true
    ];
}
