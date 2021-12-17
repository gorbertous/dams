<?php
declare(strict_types=1);

namespace Treasury\Model\Entity;

use Cake\ORM\Entity;

/**
 * Instruction Entity
 *
 * @property int $instr_num
 * @property string $instr_type
 * @property string $instr_status
 * @property \Cake\I18n\FrozenDate|null $instr_date
 * @property int|null $notify
 * @property int $notified
 * @property \Cake\I18n\FrozenDate|null $notify_date
 * @property int $mandate_ID
 * @property int $cpty_ID
 * @property string|null $created_by
 * @property \Cake\I18n\FrozenTime|null $created
 * @property string|null $validated_by
 * @property \Cake\I18n\FrozenTime|null $timestamp_validated
 * @property string|null $validated_file
 * @property string|null $pdf_by
 * @property \Cake\I18n\FrozenTime|null $timestamp_pdf
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $confirmation_file
 * @property \Cake\I18n\FrozenTime|null $confirmation_date
 * @property string|null $confirmation_by
 * @property string|null $signedDI_file
 * @property \Cake\I18n\FrozenTime|null $signedDI_date
 * @property string|null $signedDI_by
 * @property string|null $traderequest_file
 * @property \Cake\I18n\FrozenTime|null $traderequest_date
 * @property string|null $traderequest_by
 * @property \Cake\I18n\FrozenTime|null $timestamp_created
 */
class Instruction extends Entity
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
        'instr_type' => true,
        'instr_status' => true,
        'instr_date' => true,
        'notify' => true,
        'notified' => true,
        'notify_date' => true,
        'mandate_ID' => true,
        'cpty_ID' => true,
        'created_by' => true,
        'created' => true,
        'validated_by' => true,
        'timestamp_validated' => true,
        'validated_file' => true,
        'pdf_by' => true,
        'timestamp_pdf' => true,
        'modified' => true,
        'confirmation_file' => true,
        'confirmation_date' => true,
        'confirmation_by' => true,
        'signedDI_file' => true,
        'signedDI_date' => true,
        'signedDI_by' => true,
        'traderequest_file' => true,
        'traderequest_date' => true,
        'traderequest_by' => true,
        'timestamp_created' => true,
    ];
}
