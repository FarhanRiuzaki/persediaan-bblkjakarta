<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Stock Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate|null $date
 * @property string|null $type
 * @property string|null $ref_table
 * @property int|null $ref_id
 * @property int|null $product_id
 * @property int|null $qty
 * @property float|null $price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Ref $ref
 * @property \App\Model\Entity\Product $product
 */
class Stock extends Entity
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
        'date' => true,
        'type' => true,
        'ref_table' => true,
        'ref_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'expired' => true,
        'created' => true,
        'modified' => true,
        'ref' => true,
        'product' => true
    ];
}
