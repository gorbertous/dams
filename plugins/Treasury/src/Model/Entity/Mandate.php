<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mandate Entity
 *
 * @property int $mandate_ID
 * @property string|null $BU
 * @property string|null $BU_PS
 * @property string|null $mandate_name
 * @property int|null $SP_ID
 * @property int|null $TM1_ID
 * @property int|null $TM2_ID
 * @property int|null $TM3_ID
 * @property int|null $TM4_ID
 * @property int|null $TM5_ID
 * @property int|null $TM6_ID
 * @property int|null $TM7_ID
 * @property int|null $TM8_ID
 * @property int|null $TM9_ID
 * @property int|null $TM10_ID
 * @property int|null $TM11_ID
 * @property int|null $TM12_ID
 * @property int|null $TM13_ID
 * @property int|null $TM14_ID
 * @property int|null $TM15_ID
 * @property int|null $TM16_ID
 * @property int|null $TM17_ID
 * @property int|null $TM18_ID
 * @property int|null $TM19_ID
 * @property int|null $TM20_ID
 * @property int|null $TM21_ID
 * @property int|null $TM22_ID
 * @property int|null $TM23_ID
 * @property int|null $TM24_ID
 * @property int|null $TM25_ID
 * @property int|null $TM26_ID
 * @property int|null $TM27_ID
 * @property int|null $TM28_ID
 * @property int|null $TM29_ID
 * @property int|null $TM30_ID
 * @property int|null $TM31_ID
 * @property int|null $TM32_ID
 * @property int|null $TM33_ID
 * @property int|null $TM34_ID
 * @property int|null $TM35_ID
 * @property int|null $TM36_ID
 * @property int|null $TM37_ID
 * @property int|null $TM38_ID
 * @property int|null $TM39_ID
 * @property int|null $TM40_ID
 * @property int|null $TM41_ID
 * @property int|null $TM42_ID
 * @property int|null $TM43_ID
 * @property int|null $TM44_ID
 * @property int|null $TM45_ID
 * @property int|null $TM46_ID
 * @property int|null $TM47_ID
 * @property int|null $TM48_ID
 * @property int|null $TM49_ID
 * @property int|null $TM50_ID
 * @property string $to_book
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Treasury\Model\Entity\BondsTransaction[] $bonds_transactions
 */
class Mandate extends Entity
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
        'BU' => true,
        'BU_PS' => true,
        'mandate_name' => true,
        'SP_ID' => true,
        'TM1_ID' => true,
        'TM2_ID' => true,
        'TM3_ID' => true,
        'TM4_ID' => true,
        'TM5_ID' => true,
        'TM6_ID' => true,
        'TM7_ID' => true,
        'TM8_ID' => true,
        'TM9_ID' => true,
        'TM10_ID' => true,
        'TM11_ID' => true,
        'TM12_ID' => true,
        'TM13_ID' => true,
        'TM14_ID' => true,
        'TM15_ID' => true,
        'TM16_ID' => true,
        'TM17_ID' => true,
        'TM18_ID' => true,
        'TM19_ID' => true,
        'TM20_ID' => true,
        'TM21_ID' => true,
        'TM22_ID' => true,
        'TM23_ID' => true,
        'TM24_ID' => true,
        'TM25_ID' => true,
        'TM26_ID' => true,
        'TM27_ID' => true,
        'TM28_ID' => true,
        'TM29_ID' => true,
        'TM30_ID' => true,
        'TM31_ID' => true,
        'TM32_ID' => true,
        'TM33_ID' => true,
        'TM34_ID' => true,
        'TM35_ID' => true,
        'TM36_ID' => true,
        'TM37_ID' => true,
        'TM38_ID' => true,
        'TM39_ID' => true,
        'TM40_ID' => true,
        'TM41_ID' => true,
        'TM42_ID' => true,
        'TM43_ID' => true,
        'TM44_ID' => true,
        'TM45_ID' => true,
        'TM46_ID' => true,
        'TM47_ID' => true,
        'TM48_ID' => true,
        'TM49_ID' => true,
        'TM50_ID' => true,
        'to_book' => true,
        'created' => true,
        'modified' => true,
        'bonds_transactions' => true,
    ];
}
