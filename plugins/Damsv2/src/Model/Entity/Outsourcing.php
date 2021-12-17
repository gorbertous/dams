<?php

declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;

/**
 * Outsourcing Entity
 *
 * @property int $log_id
 * @property string $period_quarter
 * @property int $period_year
 * @property string $deal_business_key
 * @property string $deal_name
 * @property int $portfolio_id
 * @property string $portfolio_name
 * @property int $mandate_id
 * @property \Cake\I18n\FrozenDate $inclusion_deadline
 * @property string|null $prioritised
 * @property string $inclusion_status
 * @property \Cake\I18n\FrozenDate|null $email_date
 * @property string|null $dh_resp
 * @property string|null $inclusion_resp
 * @property \Cake\I18n\FrozenDate|null $received_date
 * @property \Cake\I18n\FrozenDate|null $first_email_date
 * @property \Cake\I18n\FrozenDate|null $inclusion_date
 * @property string|null $c_sheet
 * @property string|null $follow_up
 * @property string|null $comments
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \Damsv2\Model\Entity\Mandate $mandate
 * @property \Damsv2\Model\Entity\Portfolio $portfolio
 */
class Outsourcing extends Entity
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
        'period_quarter' => true,
        'period_year' => true,
        'deal_business_key' => true,
        'deal_name' => true,
        'portfolio_id' => true,
        'portfolio_name' => true,
        'mandate_id' => true,
        'mandate' => true,
        'inclusion_deadline' => true,
        'prioritised' => true,
        'inclusion_status' => true,
        'email_date' => true,
        'dh_resp' => true,
        'inclusion_resp' => true,
        'received_date' => true,
        'first_email_date' => true,
        'inclusion_date' => true,
        'c_sheet' => true,
        'follow_up' => true,
        'comments' => true,
        'created' => true,
        'modified' => true,
        'portfolio' => true,
    ];
}
