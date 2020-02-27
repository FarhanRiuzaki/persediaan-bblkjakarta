<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InternalOrder Entity
 *
 * @property int $id
 * @property int $institute_id
 * @property string $code
 * @property \Cake\I18n\FrozenDate $date
 * @property string|null $info
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property \Cake\I18n\FrozenTime|null $approve_date
 * @property int|null $approve_by
 *
 * @property \App\Model\Entity\Institute $institute
 * @property \App\Model\Entity\ExpendituresDistribution[] $expenditures_distributions
 * @property \App\Model\Entity\ExpendituresDistributionsDetail[] $expenditures_distributions_details
 * @property \App\Model\Entity\InternalOrdersDetail[] $internal_orders_details
 */
class InternalOrder extends Entity
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
        'institute_id' => true,
        'code' => true,
        'date' => true,
        'info' => true,
        'status' => true,
        'type' => true,
        'created' => true,
        'modified' => true,
        'created_by' => true,
        'modified_by' => true,
        'approve_date' => true,
        'approve_by' => true,
        'institute' => true,
        'expenditures_distributions' => true,
        'expenditures_distributions_details' => true,
        'internal_orders_details' => true
    ];
}
