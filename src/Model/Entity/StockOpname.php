<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockOpname Entity
 *
 * @property int $id
 * @property string|null $code
 * @property \Cake\I18n\FrozenDate $date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property \App\Model\Entity\StockOpnamesDetail[] $stock_opnames_details
 */
class StockOpname extends Entity
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
        'code' => true,
        'date' => true,
        'created' => true,
        'modified' => true,
        'created_by' => true,
        'updated_by' => true,
        'stock_opnames_details' => true
    ];
}
