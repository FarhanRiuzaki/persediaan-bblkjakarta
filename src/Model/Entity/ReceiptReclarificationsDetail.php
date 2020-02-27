<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReceiptReclarificationsDetail Entity
 *
 * @property int $id
 * @property int $receipt_reclarification_id
 * @property int $expenditures_reclarifications_detail_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ReceiptReclarification $receipt_reclarification
 * @property \App\Model\Entity\ExpendituresReclarificationsDetail $expenditures_reclarifications_detail
 * @property \App\Model\Entity\Product $product
 */
class ReceiptReclarificationsDetail extends Entity
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
        'receipt_reclarification_id' => true,
        'expenditures_reclarifications_detail_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'receipt_reclarification' => true,
        'expenditures_reclarifications_detail' => true,
        'product' => true
    ];
}
