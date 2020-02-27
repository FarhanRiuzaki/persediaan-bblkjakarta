<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InternalOrdersDetail Entity
 *
 * @property int $id
 * @property int $internal_order_id
 * @property int $product_id
 * @property float $qty
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\InternalOrder $internal_order
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ExpendituresDistributionsDetail[] $expenditures_distributions_details
 */
class InternalOrdersDetail extends Entity
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
        'internal_order_id' => true,
        'product_id' => true,
        'qty' => true,
        'note' => true,
        'qty_request' => true,
        'created' => true,
        'modified' => true,
        'internal_order' => true,
        'product' => true,
        'expenditures_distributions_details' => true
    ];
}
