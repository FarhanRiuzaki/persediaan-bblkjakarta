<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpendituresOthersDetail Entity
 *
 * @property int $id
 * @property int $expenditures_other_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\ExpendituresOther $expenditures_other
 * @property \App\Model\Entity\Product $product
 */
class ExpendituresOthersDetail extends Entity
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
        'expenditures_other_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'expenditures_other' => true,
        'product' => true
    ];
}
