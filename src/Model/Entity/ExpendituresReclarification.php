<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpendituresReclarification Entity
 *
 * @property int $id
 * @property string $code
 * @property \Cake\I18n\FrozenDate $date
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\ExpendituresReclarificationsDetail[] $expenditures_reclarifications_details
 * @property \App\Model\Entity\ReceiptReclarification[] $receipt_reclarifications
 */
class ExpendituresReclarification extends Entity
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
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'expenditures_reclarifications_details' => true,
        'receipt_reclarifications' => true
    ];
}
