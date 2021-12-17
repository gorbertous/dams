<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SmeRatingMapping Model
 *
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsTo $Portfolio
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $VUser
 *
 * @method \App\Model\Entity\SmeRatingMapping newEmptyEntity()
 * @method \App\Model\Entity\SmeRatingMapping newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SmeRatingMapping[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SmeRatingMapping get($primaryKey, $options = [])
 * @method \App\Model\Entity\SmeRatingMapping findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SmeRatingMapping patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SmeRatingMapping[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SmeRatingMapping|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SmeRatingMapping saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SmeRatingMapping[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SmeRatingMapping[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SmeRatingMapping[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SmeRatingMapping[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SmeRatingMappingTable extends Table
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

        $this->setTable('sme_rating_mapping');
        $this->setDisplayField('sme_rating_mapping_id');
        $this->setPrimaryKey('sme_rating_mapping_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Damsv2.VUser', [
            'foreignKey' => 'user_id',
        ]);
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
            ->integer('sme_rating_mapping_id')
            ->allowEmptyString('sme_rating_mapping_id', null, 'create');

        $validator
            ->scalar('sme_fi_rating_scale')
            ->maxLength('sme_fi_rating_scale', 60)
            ->allowEmptyString('sme_fi_rating_scale');

        $validator
            ->scalar('sme_rating')
            ->maxLength('sme_rating', 60)
            ->allowEmptyString('sme_rating');

        $validator
            ->scalar('adjusted_sme_fi_scale')
            ->maxLength('adjusted_sme_fi_scale', 60)
            ->allowEmptyString('adjusted_sme_fi_scale');

        $validator
            ->scalar('adjusted_sme_rating')
            ->maxLength('adjusted_sme_rating', 60)
            ->allowEmptyString('adjusted_sme_rating');

        $validator
            ->scalar('equiv_ori_sme_rating')
            ->maxLength('equiv_ori_sme_rating', 60)
            ->allowEmptyString('equiv_ori_sme_rating');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolio'), ['errorField' => 'portfolio_id']);
        $rules->add($rules->existsIn(['user_id'], 'VUser'), ['errorField' => 'user_id']);

        return $rules;
    }
}
