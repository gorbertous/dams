<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * CounterpartyAccount Entity
 *
 * @property int $id
 * @property int $cpty_id
 * @property string|null $correspondent_bank
 * @property string|null $correspondent_BIC
 * @property string $currency
 * @property string|null $account_IBAN
 * @property bool|null $target
 *
 * @property \Treasury\Model\Entity\Cpty $cpty
 */
class CounterpartyAccount extends Entity
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
        'cpty_id' => true,
        'correspondent_bank' => true,
        'correspondent_BIC' => true,
        'currency' => true,
        'account_IBAN' => true,
        'target' => true,
        'cpty' => true,
    ];
}
