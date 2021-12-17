<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bank Entity
 *
 * @property string $BIC
 * @property string|null $bank_name
 * @property string|null $short_name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $zipcode
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $tel
 * @property string|null $fax
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Bank extends Entity
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
        'bank_name' => true,
        'short_name' => true,
        'address' => true,
        'city' => true,
        'country' => true,
        'zipcode' => true,
        'contact_person' => true,
        'email' => true,
        'tel' => true,
        'fax' => true,
        'created' => true,
        'modified' => true,
    ];
}
