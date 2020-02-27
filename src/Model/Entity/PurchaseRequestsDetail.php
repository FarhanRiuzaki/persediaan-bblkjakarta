<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PurchaseRequestsDetail Entity
 *
 * @property int $id
 * @property int $purchase_request_id
 * @property int $product_id
 * @property float $qty
 * @property string|null $spec
 * @property string|null $merk
 * @property string|null $no_catalog
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\PurchaseRequest $purchase_request
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\PurchaseOrdersDetail[] $purchase_orders_details
 */
class PurchaseRequestsDetail extends Entity
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
        'purchase_request_id' => true,
        'product_id' => true,
        'qty' => true,
        'qty_request' => true,
        'spec' => true,
        'merk' => true,
        'no_catalog' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'purchase_request' => true,
        'product' => true,
        'purchase_orders_details' => true
    ];
}
