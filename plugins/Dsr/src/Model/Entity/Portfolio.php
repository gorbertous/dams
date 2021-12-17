<?php
declare(strict_types=1);

namespace Dsr\Model\Entity;

use Cake\ORM\Entity;

/**
 * Portfolio Entity
 *
 * @property int $id
 * @property int|null $product_id
 * @property string|null $name
 * @property string|null $fi_name
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Dsr\Model\Entity\Product $product
 * @property \Dsr\Model\Entity\Loan[] $loans
 * @property \Dsr\Model\Entity\Report[] $reports
 */
class Portfolio extends Entity
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
        'product_id' => true,
        'name' => true,
        'fi_name' => true,
        'created' => true,
        'modified' => true,
        'product' => true,
        'dsr_report' => true,
        'loans' => true,
        'reports' => true,
    ];
}
