<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PurchaseOrdersDetail Entity
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property int|null $purchase_request_id
 * @property int $purchase_requests_detail_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\PurchaseOrder $purchase_order
 * @property \App\Model\Entity\PurchaseRequest $purchase_request
 * @property \App\Model\Entity\PurchaseRequestsDetail $purchase_requests_detail
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ReceiptPurchasesDetail[] $receipt_purchases_details
 */
class PurchaseOrdersDetail extends Entity
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
        'purchase_order_id' => true,
        'purchase_request_id' => true,
        'purchase_requests_detail_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'purchase_order' => true,
        'purchase_request' => true,
        'purchase_requests_detail' => true,
        'product' => true,
        'receipt_purchases_details' => true
    ];
}
