<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpendituresTransfersDetail Entity
 *
 * @property int $id
 * @property int $expenditures_transfer_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ExpendituresTransfer $expenditures_transfer
 * @property \App\Model\Entity\Product $product
 */
class ExpendituresTransfersDetail extends Entity
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
        'expenditures_transfer_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'expenditures_transfer' => true,
        'product' => true
    ];
}
