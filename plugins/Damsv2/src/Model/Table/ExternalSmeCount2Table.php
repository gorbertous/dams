<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CountSmesAltfinal Model
 *
 * @method \App\Model\Entity\CountSmesAltfinal newEmptyEntity()
 * @method \App\Model\Entity\CountSmesAltfinal newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal get($primaryKey, $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CountSmesAltfinal[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ExternalSmeCount2Table extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('external_sme_count2');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('country')
            ->maxLength('country', 22)
            ->allowEmptyString('exclusion_type');

        $validator
            ->scalar('country_abrv')
            ->maxLength('country_abrv', 4)
            ->allowEmptyString('coverage_implication');

        return $validator;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'analytics';
    }

}
