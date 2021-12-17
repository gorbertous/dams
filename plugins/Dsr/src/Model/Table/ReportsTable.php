<?php
declare(strict_types=1);

namespace Dsr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reports Model
 *
 * @property \Dsr\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 * @property \Dsr\Model\Table\LoansTable&\Cake\ORM\Association\HasMany $Loans
 *
 * @method \Dsr\Model\Entity\Report newEmptyEntity()
 * @method \Dsr\Model\Entity\Report newEntity(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Report[] newEntities(array $data, array $options = [])
 * @method \Dsr\Model\Entity\Report get($primaryKey, $options = [])
 * @method \Dsr\Model\Entity\Report findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Dsr\Model\Entity\Report patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Report[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Dsr\Model\Entity\Report|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Report saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Dsr\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Dsr\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportsTable extends Table
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

        $this->setTable('reports');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Dsr.Portfolios', [
            'foreignKey' => 'portfolio_id',
        ]);
        $this->hasMany('Dsr.Loans', [
            'foreignKey' => 'report_id',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('period_quarter')
            ->maxLength('period_quarter', 2)
            ->allowEmptyString('period_quarter');

        $validator
            ->allowEmptyString('period_year');

        $validator
            ->date('report_date')
            ->allowEmptyDate('report_date');

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
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);

        return $rules;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'dsr';
    }
}
