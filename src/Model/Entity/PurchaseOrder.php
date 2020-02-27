<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PurchaseOrder Entity
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $nomor_spk
 * @property \Cake\I18n\FrozenDate $date
 * @property \Cake\I18n\FrozenDate|null $date_freeze
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 * @property \Cake\I18n\FrozenTime|null $approve_date
 * @property int|null $approve_by
 *
 * @property \App\Model\Entity\Supplier $supplier
 * @property \App\Model\Entity\PurchaseOrdersDetail[] $purchase_orders_details
 * @property \App\Model\Entity\ReceiptPurchase[] $receipt_purchases
 */
class PurchaseOrder extends Entity
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
        'supplier_id' => true,
        'nomor_spk' => true,
        'date' => true,
        'date_freeze' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'approve_date' => true,
        'approve_by' => true,
        'supplier' => true,
        'purchase_orders_details' => true,
        'receipt_purchases' => true
    ];
}
