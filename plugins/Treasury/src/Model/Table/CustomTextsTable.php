<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomTexts Model
 *
 * @property \Treasury\Model\Table\CptiesTable&\Cake\ORM\Association\BelongsTo $Cpties
 *
 * @method \Treasury\Model\Entity\CustomText newEmptyEntity()
 * @method \Treasury\Model\Entity\CustomText newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CustomText[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\CustomText get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\CustomText findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\CustomText patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CustomText[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\CustomText|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CustomText saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\CustomText[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CustomText[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CustomText[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\CustomText[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomTextsTable extends Table
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

        $this->setTable('custom_texts');
        $this->setDisplayField('custom_id');
        $this->setPrimaryKey('custom_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Cpties', [
            'foreignKey' => 'cpty_id',
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
            ->integer('custom_id')
            ->allowEmptyString('custom_id', null, 'create');

        $validator
            ->scalar('dropdown_txt')
            ->maxLength('dropdown_txt', 255)
            ->allowEmptyString('dropdown_txt');

        $validator
            ->scalar('custom_txt')
            ->maxLength('custom_txt', 255)
            ->allowEmptyString('custom_txt');

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
        $rules->add($rules->existsIn(['cpty_id'], 'Cpties'), ['errorField' => 'cpty_id']);

        return $rules;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'treasury';
    }
}
