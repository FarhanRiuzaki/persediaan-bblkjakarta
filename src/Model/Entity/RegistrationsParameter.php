<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RegistrationsParameter Entity
 *
 * @property int $id
 * @property int|null $ref_id
 * @property int|null $registration_id
 * @property int $registration_sample_id
 * @property int|null $inspection_parameter_id
 * @property int|null $inspection_package_id
 * @property float $price
 * @property int $qty
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Ref $ref
 * @property \App\Model\Entity\Registration $registration
 * @property \App\Model\Entity\RegistrationSample $registration_sample
 * @property \App\Model\Entity\InspectionParameter $inspection_parameter
 * @property \App\Model\Entity\InspectionPackage $inspection_package
 */
class RegistrationsParameter extends Entity
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
        'ref_id' => true,
        'registration_id' => true,
        'registration_sample_id' => true,
        'inspection_parameter_id' => true,
        'inspection_package_id' => true,
        'price' => true,
        'qty' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'ref' => true,
        'registration' => true,
        'registration_sample' => true,
        'inspection_parameter' => true,
        'inspection_package' => true
    ];
}
