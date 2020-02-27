<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UseInstitute Entity
 *
 * @property int $id
 * @property string|null $code
 * @property \Cake\I18n\FrozenDate|null $date
 * @property string|null $type
 * @property int|null $institute_id
 * @property int|null $registration_id
 * @property int|null $inspection_parameter_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $created_by
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\Institute $institute
 * @property \App\Model\Entity\Registration $registration
 * @property \App\Model\Entity\InspectionParameter $inspection_parameter
 * @property \App\Model\Entity\UseInstitutesDetail[] $use_institutes_details
 */
class UseInstitute extends Entity
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
        'type' => true,
        'institute_id' => true,
        'registration_id' => true,
        'inspection_parameter_id' => true,
        'created' => true,
        'modified' => true,
        'created_by' => true,
        'modified_by' => true,
        'institute' => true,
        'registration' => true,
        'inspection_parameter' => true,
        'use_institutes_details' => true
    ];
}
