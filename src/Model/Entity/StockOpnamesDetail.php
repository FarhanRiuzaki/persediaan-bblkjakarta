<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockOpnamesDetail Entity
 *
 * @property int $id
 * @property int $stock_opname_id
 * @property int|null $product_id
 * @property float|null $last_qty
 * @property float $qty
 * @property float $qty_diff
 * @property float|null $price
 * @property string|null $info
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\StockOpname $stock_opname
 * @property \App\Model\Entity\Product $product
 */
class StockOpnamesDetail extends Entity
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
        'stock_opname_id' => true,
        'product_id' => true,
        'last_qty' => true,
        'qty' => true,
        'qty_diff' => true,
        'price' => true,
        'info' => true,
        'created' => true,
        'modified' => true,
        'stock_opname' => true,
        'product' => true
    ];
}
