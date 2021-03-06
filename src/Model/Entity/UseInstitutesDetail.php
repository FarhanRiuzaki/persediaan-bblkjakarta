<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UseInstitutesDetail Entity
 *
 * @property int $id
 * @property int|null $use_institute_id
 * @property int|null $product_id
 * @property float|null $qty
 * @property string|null $unit
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\UseInstitute $use_institute
 * @property \App\Model\Entity\Product $product
 */
class UseInstitutesDetail extends Entity
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
        'use_institute_id' => true,
        'product_id' => true,
        'qty' => true,
        'unit' => true,
        'created' => true,
        'modified' => true,
        'use_institute' => true,
        'product' => true
    ];
}
