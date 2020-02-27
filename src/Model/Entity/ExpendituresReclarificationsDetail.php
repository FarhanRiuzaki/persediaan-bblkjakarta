<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpendituresReclarificationsDetail Entity
 *
 * @property int $id
 * @property int $expenditures_reclarification_id
 * @property int $product_id
 * @property int|null $qty
 * @property float|null $price
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ExpendituresReclarification $expenditures_reclarification
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ReceiptReclarificationsDetail[] $receipt_reclarifications_details
 */
class ExpendituresReclarificationsDetail extends Entity
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
        'expenditures_reclarification_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'expenditures_reclarification' => true,
        'product' => true,
        'receipt_reclarifications_details' => true
    ];
}
