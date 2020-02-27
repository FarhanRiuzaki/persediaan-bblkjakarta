<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItemHandoversDetail Entity
 *
 * @property int $id
 * @property int $item_receipt_id
 * @property int|null $product_id
 * @property float|null $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\ItemReceipt $item_receipt
 * @property \App\Model\Entity\Product $product
 */
class ItemHandoversDetail extends Entity
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
        'item_handover_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'item_handover' => true,
        'product' => true
    ];
}
