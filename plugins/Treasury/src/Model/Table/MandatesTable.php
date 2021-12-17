<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mandates Model
 *
 * @property \Treasury\Model\Table\BondsTransactionsTable&\Cake\ORM\Association\HasMany $BondsTransactions
 *
 * @method \Treasury\Model\Entity\Mandate newEmptyEntity()
 * @method \Treasury\Model\Entity\Mandate newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Mandate[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Mandate get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Mandate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Mandate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Mandate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Mandate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Mandate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Mandate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MandatesTable extends Table
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

        $this->setTable('mandates');
        $this->setDisplayField('mandate_ID');
        $this->setPrimaryKey('mandate_ID');

        $this->addBehavior('Timestamp');

        $this->hasMany('BondsTransactions', [
            'foreignKey' => 'mandate_id',
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
            ->integer('mandate_ID')
            ->allowEmptyString('mandate_ID', null, 'create');

        $validator
            ->scalar('BU')
            ->maxLength('BU', 10)
            ->allowEmptyString('BU');

        $validator
            ->scalar('BU_PS')
            ->maxLength('BU_PS', 10)
            ->allowEmptyString('BU_PS');

        $validator
            ->scalar('mandate_name')
            ->maxLength('mandate_name', 80)
            ->allowEmptyString('mandate_name');

        $validator
            ->integer('SP_ID')
            ->allowEmptyString('SP_ID');

        $validator
            ->integer('TM1_ID')
            ->allowEmptyString('TM1_ID');

        $validator
            ->integer('TM2_ID')
            ->allowEmptyString('TM2_ID');

        $validator
            ->integer('TM3_ID')
            ->allowEmptyString('TM3_ID');

        $validator
            ->integer('TM4_ID')
            ->allowEmptyString('TM4_ID');

        $validator
            ->integer('TM5_ID')
            ->allowEmptyString('TM5_ID');

        $validator
            ->integer('TM6_ID')
            ->allowEmptyString('TM6_ID');

        $validator
            ->integer('TM7_ID')
            ->allowEmptyString('TM7_ID');

        $validator
            ->integer('TM8_ID')
            ->allowEmptyString('TM8_ID');

        $validator
            ->integer('TM9_ID')
            ->allowEmptyString('TM9_ID');

        $validator
            ->integer('TM10_ID')
            ->allowEmptyString('TM10_ID');

        $validator
            ->integer('TM11_ID')
            ->allowEmptyString('TM11_ID');

        $validator
            ->integer('TM12_ID')
            ->allowEmptyString('TM12_ID');

        $validator
            ->integer('TM13_ID')
            ->allowEmptyString('TM13_ID');

        $validator
            ->integer('TM14_ID')
            ->allowEmptyString('TM14_ID');

        $validator
            ->integer('TM15_ID')
            ->allowEmptyString('TM15_ID');

        $validator
            ->integer('TM16_ID')
            ->allowEmptyString('TM16_ID');

        $validator
            ->integer('TM17_ID')
            ->allowEmptyString('TM17_ID');

        $validator
            ->integer('TM18_ID')
            ->allowEmptyString('TM18_ID');

        $validator
            ->integer('TM19_ID')
            ->allowEmptyString('TM19_ID');

        $validator
            ->integer('TM20_ID')
            ->allowEmptyString('TM20_ID');

        $validator
            ->integer('TM21_ID')
            ->allowEmptyString('TM21_ID');

        $validator
            ->integer('TM22_ID')
            ->allowEmptyString('TM22_ID');

        $validator
            ->integer('TM23_ID')
            ->allowEmptyString('TM23_ID');

        $validator
            ->integer('TM24_ID')
            ->allowEmptyString('TM24_ID');

        $validator
            ->integer('TM25_ID')
            ->allowEmptyString('TM25_ID');

        $validator
            ->integer('TM26_ID')
            ->allowEmptyString('TM26_ID');

        $validator
            ->integer('TM27_ID')
            ->allowEmptyString('TM27_ID');

        $validator
            ->integer('TM28_ID')
            ->allowEmptyString('TM28_ID');

        $validator
            ->integer('TM29_ID')
            ->allowEmptyString('TM29_ID');

        $validator
            ->integer('TM30_ID')
            ->allowEmptyString('TM30_ID');

        $validator
            ->integer('TM31_ID')
            ->allowEmptyString('TM31_ID');

        $validator
            ->integer('TM32_ID')
            ->allowEmptyString('TM32_ID');

        $validator
            ->integer('TM33_ID')
            ->allowEmptyString('TM33_ID');

        $validator
            ->integer('TM34_ID')
            ->allowEmptyString('TM34_ID');

        $validator
            ->integer('TM35_ID')
            ->allowEmptyString('TM35_ID');

        $validator
            ->integer('TM36_ID')
            ->allowEmptyString('TM36_ID');

        $validator
            ->integer('TM37_ID')
            ->allowEmptyString('TM37_ID');

        $validator
            ->integer('TM38_ID')
            ->allowEmptyString('TM38_ID');

        $validator
            ->integer('TM39_ID')
            ->allowEmptyString('TM39_ID');

        $validator
            ->integer('TM40_ID')
            ->allowEmptyString('TM40_ID');

        $validator
            ->integer('TM41_ID')
            ->allowEmptyString('TM41_ID');

        $validator
            ->integer('TM42_ID')
            ->allowEmptyString('TM42_ID');

        $validator
            ->integer('TM43_ID')
            ->allowEmptyString('TM43_ID');

        $validator
            ->integer('TM44_ID')
            ->allowEmptyString('TM44_ID');

        $validator
            ->integer('TM45_ID')
            ->allowEmptyString('TM45_ID');

        $validator
            ->integer('TM46_ID')
            ->allowEmptyString('TM46_ID');

        $validator
            ->integer('TM47_ID')
            ->allowEmptyString('TM47_ID');

        $validator
            ->integer('TM48_ID')
            ->allowEmptyString('TM48_ID');

        $validator
            ->integer('TM49_ID')
            ->allowEmptyString('TM49_ID');

        $validator
            ->integer('TM50_ID')
            ->allowEmptyString('TM50_ID');

        $validator
            ->scalar('to_book')
            ->maxLength('to_book', 1)
            ->notEmptyString('to_book');

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
