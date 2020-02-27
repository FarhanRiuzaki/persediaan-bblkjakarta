<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExportProductsDetail Entity
 *
 * @property int $id
 * @property int $export_product_id
 * @property \Cake\I18n\FrozenDate|null $date
 * @property int|null $qty
 * @property int $status
 *
 * @property \App\Model\Entity\ExportProduct $export_product
 */
class ExportProductsDetail extends Entity
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
        'export_product_id' => true,
        'product_id' => true,
        'date' => true,
        'qty' => true,
        'qty_in' => true,
        'status' => true,
        'export_product' => true
    ];
}
