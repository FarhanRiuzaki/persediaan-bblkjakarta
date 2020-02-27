<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItemReceipt Entity
 *
 * @property int $id
 * @property string $code
 * @property string|null $category
 * @property \Cake\I18n\FrozenDate $date
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\ItemReceiptsDetail[] $item_receipts_details
 */
class ItemReceipt extends Entity
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
        'category' => true,
        'doc_name' => true,
        'doc_type' => true,
        'date' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'item_receipts_details' => true
    ];
}
