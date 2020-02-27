<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReceiptPurchasesDetail Entity
 *
 * @property int $id
 * @property int $receipt_purchase_id
 * @property int $purchase_orders_detail_id
 * @property int $product_id
 * @property float $qty
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ReceiptPurchase $receipt_purchase
 * @property \App\Model\Entity\PurchaseOrdersDetail $purchase_orders_detail
 * @property \App\Model\Entity\Product $product
 */
class ReceiptPurchasesDetail extends Entity
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
        'receipt_purchase_id' => true,
        'purchase_orders_detail_id' => true,
        'product_id' => true,
        'qty' => true,
        'created' => true,
        'modified' => true,
        'receipt_purchase' => true,
        'purchase_orders_detail' => true,
        'product' => true
    ];
}
