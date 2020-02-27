<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpendituresTransfer Entity
 *
 * @property int $id
 * @property string $code
 * @property int $institution_id
 * @property \Cake\I18n\FrozenDate $date
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\Institution $institution
 * @property \App\Model\Entity\ExpendituresTransfersDetail[] $expenditures_transfers_details
 */
class ExpendituresTransfer extends Entity
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
        'institution_id' => true,
        'date' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'institution' => true,
        'expenditures_transfers_details' => true
    ];
}
