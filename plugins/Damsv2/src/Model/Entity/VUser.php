<?php

declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * VUser Entity
 *
 * @property float|null $id
 * @property string|null $first_name
 * @property string|null $last_name
 */
class VUser extends Entity
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
        'id'         => true,
        'first_name' => true,
        'last_name'  => true,
    ];

    protected function _getFullName()
    {
        $last = $this->last_name === 'NULL' ? '' : $this->last_name;
        $first = !empty($this->first_name) ? $this->first_name : '';
        $full_name = $last . ' ' . $first;
        return $full_name;
    }

    protected function _getLabel()
    {
        return $this->_fields['first_name'] . ' ' . $this->_fields['last_name']
                . ' / ' . __('User ID %s', $this->_fields['id']);
    }

}
