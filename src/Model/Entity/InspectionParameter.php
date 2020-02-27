<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InspectionParameter Entity
 *
 * @property int $id
 * @property string $code_parameter
 * @property int|null $code_sysmex
 * @property int $institute_id
 * @property int|null $sub_institute_id
 * @property string $name
 * @property string|null $method
 * @property float $price
 * @property string|null $unit
 * @property float|null $duration
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\Institute $institute
 * @property \App\Model\Entity\SubInstitute $sub_institute
 * @property \App\Model\Entity\RegistrationsParameter[] $registrations_parameters
 * @property \App\Model\Entity\UseInstitute[] $use_institutes
 */
class InspectionParameter extends Entity
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
        'id' => true,
        'code_parameter' => true,
        'code_sysmex' => true,
        'institute_id' => true,
        'sub_institute_id' => true,
        'name' => true,
        'method' => true,
        'price' => true,
        'unit' => true,
        'duration' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'institute' => true,
        'sub_institute' => true,
        'registrations_parameters' => true,
        'use_institutes' => true
    ];
}
