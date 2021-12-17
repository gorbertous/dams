<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DiTemplates Model
 *
 * @method \Treasury\Model\Entity\DiTemplate newEmptyEntity()
 * @method \Treasury\Model\Entity\DiTemplate newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\DiTemplate[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\DiTemplate get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\DiTemplate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\DiTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\DiTemplate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\DiTemplate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\DiTemplate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\DiTemplate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\DiTemplate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\DiTemplate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\DiTemplate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DiTemplatesTable extends Table
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

        $this->setTable('di_templates');
        $this->setDisplayField('dit_id');
        $this->setPrimaryKey('dit_id');

        $this->addBehavior('Timestamp');
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
            ->allowEmptyString('dit_id', null, 'create');

        $validator
            ->scalar('template')
            ->maxLength('template', 128)
            ->allowEmptyString('template');

        $validator
            ->allowEmptyString('mandate_ID');

        $validator
            ->allowEmptyString('cpty_ID');

        $validator
            ->scalar('attn')
            ->maxLength('attn', 256)
            ->allowEmptyString('attn');

        $validator
            ->scalar('preamble')
            ->maxLength('preamble', 16777215)
            ->allowEmptyString('preamble');

        $validator
            ->scalar('deposits_footer')
            ->maxLength('deposits_footer', 16777215)
            ->allowEmptyString('deposits_footer');

        $validator
            ->integer('footer_force')
            ->allowEmptyString('footer_force');

        return $validator;
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
