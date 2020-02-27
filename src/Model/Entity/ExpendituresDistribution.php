<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExpendituresDistribution Entity
 *
 * @property int $id
 * @property string $code
 * @property int $institute_id
 * @property int $internal_order_id
 * @property \Cake\I18n\FrozenDate $date
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\Institute $institute
 * @property \App\Model\Entity\InternalOrder $internal_order
 * @property \App\Model\Entity\ExpendituresDistributionsDetail[] $expenditures_distributions_details
 */
class ExpendituresDistribution extends Entity
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
        'institute_id' => true,
        'internal_order_id' => true,
        'date' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'institute' => true,
        'internal_order' => true,
        'expenditures_distributions_details' => true
    ];
}
