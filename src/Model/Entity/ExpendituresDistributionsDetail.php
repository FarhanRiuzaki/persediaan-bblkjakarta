<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpendituresDistributionsDetail Entity
 *
 * @property int $id
 * @property int $expenditures_distribution_id
 * @property int|null $internal_order_id
 * @property int|null $internal_orders_detail_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ExpendituresDistribution $expenditures_distribution
 * @property \App\Model\Entity\InternalOrder $internal_order
 * @property \App\Model\Entity\InternalOrdersDetail $internal_orders_detail
 * @property \App\Model\Entity\Product $product
 */
class ExpendituresDistributionsDetail extends Entity
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
        'expenditures_distribution_id' => true,
        'internal_order_id' => true,
        'internal_orders_detail_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
        'expenditures_distribution' => true,
        'internal_order' => true,
        'internal_orders_detail' => true,
        'product' => true
    ];
}
