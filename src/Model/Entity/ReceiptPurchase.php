<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReceiptPurchase Entity
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property string $code
 * @property \Cake\I18n\FrozenDate $date
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\PurchaseOrder $purchase_order
 * @property \App\Model\Entity\ReceiptPurchasesDetail[] $receipt_purchases_details
 */
class ReceiptPurchase extends Entity
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
        'code' => true,
        'date' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'purchase_order' => true,
        'receipt_purchases_details' => true
    ];
}
