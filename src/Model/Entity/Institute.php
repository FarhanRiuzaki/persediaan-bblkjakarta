<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Institute Entity
 *
 * @property int $id
 * @property string $name
 * @property string $head_of_institute_id
 * @property string $position_head_institute
 * @property string $code_lab
 * @property string $code_insititute
 * @property string $code_serial
 * @property float $time_up
 * @property string $unit_time_up
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\HeadOfInstitute $head_of_institute
 * @property \App\Model\Entity\ExpendituresDistribution[] $expenditures_distributions
 * @property \App\Model\Entity\InspectionParameter[] $inspection_parameters
 * @property \App\Model\Entity\InternalOrder[] $internal_orders
 * @property \App\Model\Entity\PurchaseRequest[] $purchase_requests
 * @property \App\Model\Entity\StockInstitute[] $stock_institutes
 * @property \App\Model\Entity\UseInstitute[] $use_institutes
 */
class Institute extends Entity
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
        'name' => true,
        'head_of_institute' => true,
        'head_of_institute_id' => true,
        'position_head_institute' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'expenditures_distributions' => true,
        'inspection_parameters' => true,
        'internal_orders' => true,
        'purchase_requests' => true,
        'stock_institutes' => true,
        'use_institutes' => true
    ];
}
