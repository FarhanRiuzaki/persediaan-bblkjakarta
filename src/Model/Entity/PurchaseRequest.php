<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PurchaseRequest Entity
 *
 * @property int $id
 * @property int $institute_id
 * @property string $code
 * @property \Cake\I18n\FrozenDate $date
 * @property int|null $status
 * @property string|null $info
 * @property \Cake\I18n\FrozenTime $created
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 * @property \Cake\I18n\FrozenTime|null $approve_date
 * @property int|null $approve_by
 *
 * @property \App\Model\Entity\Institute $institute
 * @property \App\Model\Entity\PurchaseOrdersDetail[] $purchase_orders_details
 * @property \App\Model\Entity\PurchaseRequestsDetail[] $purchase_requests_details
 */
class PurchaseRequest extends Entity
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
        'institute_id' => true,
        'code' => true,
        'date' => true,
        'type' => true,
        'status' => true,
        'info' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'approve_date' => true,
        'approve_by' => true,
        'institute' => true,
        'purchase_orders_details' => true,
        'purchase_requests_details' => true
    ];
}
