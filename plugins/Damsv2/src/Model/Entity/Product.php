<?php

declare(strict_types=1);

namespace Damsv2\Model\Entity;

use Cake\ORM\Entity;


/**
 * Product Entity
 *
 * @property int $product_id
 * @property string $name
 * @property string|null $product_type
 * @property string $capped
 * @property string $fixed_rate
 * @property string|null $reporting_frequency
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Product extends Entity
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
        'name'                => true,
        'product_type'        => true,
        'capped'              => true,
        'fixed_rate'          => true,
        'reporting_frequency' => true,
        'created'             => true,
        'modified'            => true,
    ];

    // for the dropdowns, do not include MAP and SME GF
//    public function getProducts()
//    {
//        return $this->find('list', [
//                    'fields'     => ['Product.product_id', 'Product.name'],
//                    'order'      => ['Product.name'],
//                    'conditions' => ['Product.product_id NOT IN' => [22, 23]] //do not include MAP and SME GF
//        ]);
//    }

}
