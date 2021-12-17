<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Template Model
 *
 * @property \App\Model\Table\TemplateTypesTable&\Cake\ORM\Association\BelongsTo $TemplateType
 * @property \App\Model\Table\CallbacksTable&\Cake\ORM\Association\BelongsTo $Callbacks
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsToMany $Portfolio
 *
 * @method \App\Model\Entity\Template newEmptyEntity()
 * @method \App\Model\Entity\Template newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Template[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Template get($primaryKey, $options = [])
 * @method \App\Model\Entity\Template findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Template patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Template[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Template|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Template saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Template[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Template[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Template[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Template[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TemplateTable extends Table
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

        $this->setTable('template');
        $this->setDisplayField('name');
        $this->setPrimaryKey('template_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.TemplateType', [
            'foreignKey' => 'template_type_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsToMany('Damsv2.Portfolio', [
            'foreignKey'       => 'template_id',
            'targetForeignKey' => 'portfolio_id',
            'joinTable'        => 'template_portfolio',
        ]);
    }

    public function getSheetsByPortfolioIdForEdit($portfolio_id = null)
    {
        $result = [];
        if ($portfolio_id != null) {
            $templates_names = ['A1'  => 'SME',
                'A2'  => 'Transactions',
                'A3'  => 'Guarantees',
                'B'   => 'Included',
                'D'   => 'Expired',
                'E'   => 'Excluded',
                'C'   => 'Defaulted',
                'R'   => 'Recoveries',
                'IR'  => 'Initial rating',
                'CR'  => 'Current rating',
                'GGE' => 'GGE',
                'I1'  => 'Re-performing start',
                'I2'  => 'Re-performing end',
                'H'   => 'Revolving',
                'H'   => 'Converted Operations',
                'EP'  => 'Expired to Performing',
                'BDS' => 'BDS',
                'TBE' => 'To be Excluded',
                'A21' => 'Subtransactions',
                'B1'  => 'Included Subtransactions',
            ];

            if ($this->portfolioIsEsifAgriPrsl($portfolio_id)) {
                $templates_names['H'] = 'Converted Operations';
            }
            $connection = ConnectionManager::get('default');

            $sheets = $connection->execute("SELECT mt.sheet_name, mt.table_name FROM mapping_table mt, template t, template_portfolio tp WHERE mt.template_id = t.template_id AND t.template_type_id IN (5,6,7,8,9,13,15,16) AND tp.template_id = t.template_id AND tp.portfolio_id = " . intval($portfolio_id))->fetchAll('assoc');

            foreach ($sheets as $sheet) {
                //error_log('template assign : sheet :' . json_encode($sheet));
                $result[$sheet['sheet_name']] = $templates_names[$sheet['sheet_name']];
            }
        }
        return $result;
    }

    public function getSheetsByUmbrellaIdForEdit($umbrella_id = null)
    {
        $result = [];
        if ($umbrella_id != null) {
            $umbrella_id = str_replace('u_', '', $umbrella_id);
            $connection = ConnectionManager::get('default');
            $sheets = $connection->execute("SELECT mt.sheet_name, mt.table_name FROM mapping_table mt, template t, template_portfolio tp, portfolio p, umbrella_portfolio up WHERE mt.template_id = t.template_id AND t.template_type_id IN (5,6,7,8,9) AND tp.template_id = t.template_id AND tp.portfolio_id = p.portfolio_id AND p.iqid = up.iqid AND up.umbrella_portfolio_id=" . intval($umbrella_id))->fetchAll('assoc');

            $templates_names = ['A1'  => 'SME',
                'A2'  => 'Transactions',
                'A3'  => 'Guarantees',
                'B'   => 'Included',
                'D'   => 'Expired',
                'E'   => 'Excluded',
                'C'   => 'Defaulted',
                'R'   => 'Recoveries',
                'IR'  => 'Initial rating',
                'CR'  => 'Current rating',
                'GGE' => 'GGE',
                'I1'  => 'Re-performing start',
                'I2'  => 'Re-performing end',
                'H'   => 'Revolving',
                'A21' => 'Subtransactions',
                'B1'  => 'Included Subtransactions',
            ];

            foreach ($sheets as $sheet) {
                $result[$sheet['sheet_name']] = $templates_names[$sheet['sheet_name']];
            }
        }
        return $result;
    }

    public function portfolioHasPDLR($portfolio_id)
    {
        $hasLR = true; // if not found, by default we assume the portfolio has PD and LR
        $hasPD = true;
        $connection = ConnectionManager::get('default');
        if (!empty($portfolio_id)) {
            $portfolio = $connection->execute("SELECT tp.template_id FROM template t, template_portfolio tp WHERE tp.portfolio_id = " . intval($portfolio_id) . " AND tp.template_id=t.template_id AND t.template_type_id=3")->fetchAll('assoc');
            if (!empty($portfolio)) {
                $hasLR = true;
            } else {
                $hasLR = false;
            }

            $portfolio = $connection->execute("SELECT tp.template_id FROM template t, template_portfolio tp WHERE tp.portfolio_id = " . intval($portfolio_id) . " AND tp.template_id=t.template_id AND t.template_type_id=2")->fetchAll('assoc');
            if (!empty($portfolio)) {
                $hasPD = true;
            } else {
                $hasPD = false;
            }
        }
        return ['hasLR' => $hasLR, 'hasPD' => $hasPD];
    }

    public function portfolioHasTemplateBR($portfolio_id)
    {
        // searching for inclusion, PD, LR templates assigned to portfolio
        $hasInclusion = false;
        $hasLR = false;
        $hasPD = false;
        $connection = ConnectionManager::get('default');
        if (!empty($portfolio_id)) {
            $portfolio = $connection->execute("SELECT tp.template_id FROM template t, template_portfolio tp WHERE tp.portfolio_id = " . intval($portfolio_id) . " AND tp.template_id=t.template_id AND t.template_type_id=1")->fetchAll('assoc');
            if (!empty($portfolio)) {
                $hasInclusion = true;
            } else {
                $hasInclusion = false;
            }
            $PD_LR = $this->portfolioHasPDLR($portfolio_id);
            $hasLR = $PD_LR['hasLR'];
            $hasPD = $PD_LR['hasPD'];
        }
        return ['hasInclusion' => $hasInclusion, 'hasLR' => $hasLR, 'hasPD' => $hasPD];
    }

    public function portfolioIsEsifAgriPrsl($portfolio_id)
    {
        $portfolios = TableRegistry::get('Damsv2.Portfolio');
        $portfolio = $portfolios->find('all', ['conditions' => ['portfolio_id' => $portfolio_id]])->first();
        return $portfolio->product_id == 25;
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
                ->integer('template_id')
                ->allowEmptyString('template_id', null, 'create');

        $validator
                ->scalar('name')
                ->maxLength('name', 100)
                ->requirePresence('name', 'create')
                ->notEmptyString('name');

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
        $rules->add($rules->existsIn(['template_type_id'], 'TemplateType'), ['errorField' => 'template_type_id']);
        $rules->add($rules->existsIn(['callback_id'], 'Callbacks'), ['errorField' => 'callback_id']);

        return $rules;
    }

}
