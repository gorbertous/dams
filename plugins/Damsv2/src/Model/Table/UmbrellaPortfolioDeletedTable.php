<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UmbrellaPortfolioDeleted Model
 *
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\BelongsTo $Reports
 * @property \App\Model\Table\StatusesTable&\Cake\ORM\Association\BelongsTo $Statuses
 * @property \App\Model\Table\PortfoliosTable&\Cake\ORM\Association\BelongsTo $Portfolios
 *
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted newEmptyEntity()
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted get($primaryKey, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UmbrellaPortfolioDeleted[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UmbrellaPortfolioDeletedTable extends Table
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

        $this->setTable('umbrella_portfolio_deleted');
        $this->setDisplayField('id_deleted');
        $this->setPrimaryKey('id_deleted');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Report', [
            'foreignKey' => 'report_id',
        ]);
        $this->belongsTo('Damsv2.Status', [
            'foreignKey' => 'status_id',
        ]);
        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
            'joinType'   => 'INNER',
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
            ->scalar('report_name')
            ->maxLength('report_name', 100)
            ->allowEmptyString('report_name');

        $validator
            ->scalar('input_filename')
            ->maxLength('input_filename', 255)
            ->allowEmptyFile('input_filename');

        $validator
            ->integer('status_id_umbrella')
            ->allowEmptyString('status_id_umbrella');

        $validator
            ->scalar('period')
            ->maxLength('period', 20)
            ->allowEmptyString('period');

        $validator
            ->integer('id_deleted')
            ->allowEmptyString('id_deleted', null, 'create');

        $validator
            ->scalar('input_filename_umbrella')
            ->maxLength('input_filename_umbrella', 200)
            ->allowEmptyFile('input_filename_umbrella');

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
        $rules->add($rules->existsIn(['report_id'], 'Reports'), ['errorField' => 'report_id']);
        $rules->add($rules->existsIn(['status_id'], 'Statuses'), ['errorField' => 'status_id']);
        $rules->add($rules->existsIn(['portfolio_id'], 'Portfolios'), ['errorField' => 'portfolio_id']);

        return $rules;
    }
}
