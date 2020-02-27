<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Institution Entity
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $pic_name
 * @property string $pic_phone
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\ExpendituresTransfer[] $expenditures_transfers
 * @property \App\Model\Entity\ReceiptTransfer[] $receipt_transfers
 */
class Institution extends Entity
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
        'name' => true,
        'address' => true,
        'phone' => true,
        'pic_name' => true,
        'pic_phone' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'expenditures_transfers' => true,
        'receipt_transfers' => true
    ];
}
