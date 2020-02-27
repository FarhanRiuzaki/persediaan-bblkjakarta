<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InitStocksDetail Entity
 *
 * @property int $id
 * @property int $init_stock_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\InitStock $init_stock
 * @property \App\Model\Entity\Product $product
 */
class InitStocksDetail extends Entity
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
        'init_stock_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'expired' => true,
        'created' => true,
        'modified' => true,
        'init_stock' => true,
        'product' => true
    ];
}
