<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Counterparty Entity
 *
 * @property int $cpty_ID
 * @property string|null $cpty_name
 * @property string|null $cpty_code
 * @property string|null $cpty_address
 * @property string|null $cpty_city
 * @property string|null $cpty_country
 * @property string|null $cpty_zipcode
 * @property int|null $automatic_fixing
 * @property string|null $capitalisation_frequency
 * @property string|null $cpty_bic
 * @property string|null $pirat_number
 * @property string|null $eu_central_bank
 * @property string|null $contact_person1
 * @property string|null $contact_person2
 * @property string|null $tel1
 * @property string|null $tel2
 * @property string|null $fax1
 * @property string|null $fax2
 * @property string|null $email1
 * @property string|null $email2
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class Counterparty extends Entity
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
        'cpty_name' => true,
        'cpty_code' => true,
        'cpty_address' => true,
        'cpty_city' => true,
        'cpty_country' => true,
        'cpty_zipcode' => true,
        'automatic_fixing' => true,
        'capitalisation_frequency' => true,
        'cpty_bic' => true,
        'pirat_number' => true,
        'eu_central_bank' => true,
        'contact_person1' => true,
        'contact_person2' => true,
        'tel1' => true,
        'tel2' => true,
        'fax1' => true,
        'fax2' => true,
        'email1' => true,
        'email2' => true,
        'created' => true,
        'modified' => true,
    ];
}
