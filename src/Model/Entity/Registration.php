<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Registration Entity
 *
 * @property int $id
 * @property string|null $code
 * @property string $no_invoice
 * @property int $customer_id
 * @property int|null $doctor_id
 * @property string $type_invoice
 * @property \Cake\I18n\FrozenDate $date
 * @property int|null $status
 * @property int $status_payment
 * @property int $total_invoice
 * @property int|null $discount
 * @property \Cake\I18n\FrozenTime $created
 * @property int $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Doctor $doctor
 * @property \App\Model\Entity\RegistrationsParameter[] $registrations_parameters
 * @property \App\Model\Entity\UseInstitute[] $use_institutes
 */
class Registration extends Entity
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
        'code' => true,
        'no_invoice' => true,
        'customer_id' => true,
        'doctor_id' => true,
        'type_invoice' => true,
        'date' => true,
        'status' => true,
        'status_payment' => true,
        'total_invoice' => true,
        'discount' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'customer' => true,
        'doctor' => true,
        'registrations_parameters' => true,
        'use_institutes' => true
    ];
}
