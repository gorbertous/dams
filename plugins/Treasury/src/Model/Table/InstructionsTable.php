<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Instructions Model
 *
 * @method \Treasury\Model\Entity\Instruction newEmptyEntity()
 * @method \Treasury\Model\Entity\Instruction newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Instruction[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\Instruction get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\Instruction findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\Instruction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Instruction[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\Instruction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Instruction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\Instruction[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Instruction[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Instruction[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\Instruction[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InstructionsTable extends Table
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

        $this->setTable('instructions');
        $this->setDisplayField('instr_num');
        $this->setPrimaryKey('instr_num');

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
            ->integer('instr_num')
            ->allowEmptyString('instr_num', null, 'create');

        $validator
            ->scalar('instr_type')
            ->maxLength('instr_type', 45)
            ->notEmptyString('instr_type');

        $validator
            ->scalar('instr_status')
            ->maxLength('instr_status', 45)
            ->notEmptyString('instr_status');

        $validator
            ->date('instr_date')
            ->allowEmptyDate('instr_date');

        $validator
            ->integer('notify')
            ->allowEmptyString('notify');

        $validator
            ->integer('notified')
            ->notEmptyString('notified');

        $validator
            ->date('notify_date')
            ->allowEmptyDate('notify_date');

        $validator
            ->integer('mandate_ID')
            ->notEmptyString('mandate_ID');

        $validator
            ->integer('cpty_ID')
            ->notEmptyString('cpty_ID');

        $validator
            ->scalar('created_by')
            ->maxLength('created_by', 45)
            ->allowEmptyString('created_by');

        $validator
            ->scalar('validated_by')
            ->maxLength('validated_by', 45)
            ->allowEmptyString('validated_by');

        $validator
            ->dateTime('timestamp_validated')
            ->allowEmptyDateTime('timestamp_validated');

        $validator
            ->scalar('validated_file')
            ->maxLength('validated_file', 250)
            ->allowEmptyFile('validated_file');

        $validator
            ->scalar('pdf_by')
            ->maxLength('pdf_by', 45)
            ->allowEmptyString('pdf_by');

        $validator
            ->dateTime('timestamp_pdf')
            ->allowEmptyDateTime('timestamp_pdf');

        $validator
            ->scalar('confirmation_file')
            ->maxLength('confirmation_file', 250)
            ->allowEmptyFile('confirmation_file');

        $validator
            ->dateTime('confirmation_date')
            ->allowEmptyDateTime('confirmation_date');

        $validator
            ->scalar('confirmation_by')
            ->maxLength('confirmation_by', 250)
            ->allowEmptyString('confirmation_by');

        $validator
            ->scalar('signedDI_file')
            ->maxLength('signedDI_file', 250)
            ->allowEmptyFile('signedDI_file');

        $validator
            ->dateTime('signedDI_date')
            ->allowEmptyDateTime('signedDI_date');

        $validator
            ->scalar('signedDI_by')
            ->maxLength('signedDI_by', 250)
            ->allowEmptyString('signedDI_by');

        $validator
            ->scalar('traderequest_file')
            ->maxLength('traderequest_file', 250)
            ->allowEmptyFile('traderequest_file');

        $validator
            ->dateTime('traderequest_date')
            ->allowEmptyDateTime('traderequest_date');

        $validator
            ->scalar('traderequest_by')
            ->maxLength('traderequest_by', 250)
            ->allowEmptyString('traderequest_by');

        $validator
            ->dateTime('timestamp_created')
            ->allowEmptyDateTime('timestamp_created');

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
