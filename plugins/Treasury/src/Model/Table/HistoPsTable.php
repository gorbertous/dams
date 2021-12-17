<?php
declare(strict_types=1);

namespace Treasury\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HistoPs Model
 *
 * @property \Treasury\Model\Table\TransactionsTable&\Cake\ORM\Association\BelongsTo $Transactions
 *
 * @method \Treasury\Model\Entity\HistoP newEmptyEntity()
 * @method \Treasury\Model\Entity\HistoP newEntity(array $data, array $options = [])
 * @method \Treasury\Model\Entity\HistoP[] newEntities(array $data, array $options = [])
 * @method \Treasury\Model\Entity\HistoP get($primaryKey, $options = [])
 * @method \Treasury\Model\Entity\HistoP findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Treasury\Model\Entity\HistoP patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Treasury\Model\Entity\HistoP[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Treasury\Model\Entity\HistoP|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\HistoP saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Treasury\Model\Entity\HistoP[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\HistoP[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\HistoP[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Treasury\Model\Entity\HistoP[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HistoPsTable extends Table
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

        $this->setTable('histo_ps');
        $this->setDisplayField('histo_id');
        $this->setPrimaryKey('histo_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Transactions', [
            'foreignKey' => 'transaction_id',
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
            ->integer('histo_id')
            ->allowEmptyString('histo_id', null, 'create');

        $validator
            ->scalar('bu_ps')
            ->maxLength('bu_ps', 10)
            ->allowEmptyString('bu_ps');

        $validator
            ->scalar('bu_gl')
            ->maxLength('bu_gl', 10)
            ->allowEmptyString('bu_gl');

        $validator
            ->scalar('ledger_group')
            ->maxLength('ledger_group', 7)
            ->allowEmptyString('ledger_group');

        $validator
            ->integer('transaction_line')
            ->allowEmptyString('transaction_line');

        $validator
            ->date('accounting_dt')
            ->allowEmptyDate('accounting_dt');

        $validator
            ->scalar('account')
            ->maxLength('account', 10)
            ->allowEmptyString('account');

        $validator
            ->scalar('deptid')
            ->maxLength('deptid', 10)
            ->allowEmptyString('deptid');

        $validator
            ->scalar('product')
            ->maxLength('product', 1)
            ->allowEmptyString('product');

        $validator
            ->scalar('pic_tiers')
            ->maxLength('pic_tiers', 1)
            ->allowEmptyString('pic_tiers');

        $validator
            ->scalar('portefeuille')
            ->maxLength('portefeuille', 1)
            ->allowEmptyString('portefeuille');

        $validator
            ->scalar('type_de_taux')
            ->maxLength('type_de_taux', 1)
            ->allowEmptyString('type_de_taux');

        $validator
            ->scalar('idpgl')
            ->maxLength('idpgl', 1)
            ->allowEmptyString('idpgl');

        $validator
            ->scalar('code_region')
            ->maxLength('code_region', 1)
            ->allowEmptyString('code_region');

        $validator
            ->scalar('grp_produit')
            ->maxLength('grp_produit', 1)
            ->allowEmptyString('grp_produit');

        $validator
            ->scalar('origine_fond')
            ->maxLength('origine_fond', 10)
            ->allowEmptyString('origine_fond');

        $validator
            ->scalar('code_mandat')
            ->maxLength('code_mandat', 10)
            ->allowEmptyString('code_mandat');

        $validator
            ->scalar('project_code')
            ->maxLength('project_code', 50)
            ->allowEmptyString('project_code');

        $validator
            ->scalar('jrnl_ln_ref')
            ->maxLength('jrnl_ln_ref', 1)
            ->allowEmptyString('jrnl_ln_ref');

        $validator
            ->scalar('foreign_currency')
            ->maxLength('foreign_currency', 3)
            ->allowEmptyString('foreign_currency');

        $validator
            ->decimal('foreign_amount')
            ->allowEmptyString('foreign_amount');

        $validator
            ->scalar('line_descr')
            ->maxLength('line_descr', 50)
            ->allowEmptyString('line_descr');

        $validator
            ->date('abd_date')
            ->allowEmptyDate('abd_date');

        $validator
            ->scalar('trans_ref_num')
            ->maxLength('trans_ref_num', 8)
            ->allowEmptyString('trans_ref_num');

        $validator
            ->integer('tr_number')
            ->allowEmptyString('tr_number');

        $validator
            ->scalar('header_description')
            ->maxLength('header_description', 100)
            ->allowEmptyString('header_description');

        $validator
            ->scalar('revision')
            ->maxLength('revision', 11)
            ->allowEmptyString('revision');

        $validator
            ->scalar('book_type')
            ->maxLength('book_type', 2)
            ->allowEmptyString('book_type');

        $validator
            ->scalar('eom_booking')
            ->maxLength('eom_booking', 6)
            ->allowEmptyString('eom_booking');

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
        $rules->add($rules->existsIn(['transaction_id'], 'Transactions'), ['errorField' => 'transaction_id']);

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
