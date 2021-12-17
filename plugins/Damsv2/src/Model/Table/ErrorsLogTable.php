<?php

declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * ErrorsLog Model
 *
 * @property \App\Model\Table\PortfolioTable&\Cake\ORM\Association\BelongsTo $Portfolio
 * @property \App\Model\Table\ReportTable&\Cake\ORM\Association\BelongsTo $Report
 *
 * @method \App\Model\Entity\ErrorsLog newEmptyEntity()
 * @method \App\Model\Entity\ErrorsLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ErrorsLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ErrorsLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ErrorsLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ErrorsLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ErrorsLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ErrorsLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ErrorsLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ErrorsLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ErrorsLogTable extends Table
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

        $this->setTable('errors_log');
        $this->setDisplayField('error_id');
        $this->setPrimaryKey('error_id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Damsv2.Portfolio', [
            'foreignKey' => 'portfolio_id',
            'joinType'   => 'INNER',
        ]);
        $this->belongsTo('Damsv2.Report', [
            'foreignKey' => 'report_id',
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
                ->integer('error_id')
                ->allowEmptyString('error_id', null, 'create');

        $validator
                ->scalar('portfolio_name')
                ->maxLength('portfolio_name', 250)
                ->allowEmptyString('portfolio_name');

        $validator
                ->scalar('mandate')
                ->maxLength('mandate', 250)
                ->allowEmptyString('mandate');

        $validator
                ->scalar('beneficiary_name')
                ->maxLength('beneficiary_name', 250)
                ->allowEmptyString('beneficiary_name');

        $validator
                ->scalar('period')
                ->maxLength('period', 50)
                ->allowEmptyString('period');

        $validator
                ->integer('total_lines')
                ->allowEmptyString('total_lines');

        $validator
                ->integer('iterations')
                ->notEmptyString('iterations');

        $validator
                ->scalar('file_formats')
                ->maxLength('file_formats', 25)
                ->allowEmptyFile('file_formats');

        $validator
                ->integer('total_formats')
                ->allowEmptyString('total_formats');

        $validator
                ->integer('total_dictionaries')
                ->allowEmptyString('total_dictionaries');

        $validator
                ->integer('total_integrities')
                ->allowEmptyString('total_integrities');

        $validator
                ->integer('total_business_rules')
                ->allowEmptyString('total_business_rules');

        $validator
                ->integer('total_warnings')
                ->allowEmptyString('total_warnings');

        $validator
                ->scalar('fi_responsivness')
                ->maxLength('fi_responsivness', 25)
                ->allowEmptyString('fi_responsivness');

        $validator
                ->scalar('comments')
                ->allowEmptyString('comments');

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
        $rules->add($rules->existsIn(['report_id'], 'Report'), ['errorField' => 'report_id']);

        return $rules;
    }

    public function inclusionIsPdlr($report)
    {
        $isPdlr = false;
        if (!empty($report->template)) {
            if ($report->template->template_type_id == '2' || $report->template->template_type_id == '3') {
                $isPdlr = true;
            }
        }
        return $isPdlr;
    }

    /*
     * checkErrorImport
     * 	fill the table errors_log to check all import
     * 	create a new row each time you edit import of inclusion file (increment the field iterations)
     */

    public function checkErrorImport($report, $file_formats)
    {
        //exception for inclusion with template PDLR
        if ($this->inclusionIsPdlr($report)) {
            return;
        }

        $errorsLog = $this->newEmptyEntity();

        $errorsLog->portfolio_id = $report->portfolio_id;
        $errorsLog->report_id = $report->report_id;

        $lastError = $this->find('all', [
                    'conditions' => [
                        'ErrorsLog.portfolio_id' => $report->portfolio_id,
                        'ErrorsLog.report_id'    => $report->report_id
                    ],
                    'order'      => [
                        'ErrorsLog.created DESC'
                    ]
                ])->first();

        $errorsLog->iterations = 1;
        if (!empty($lastError)) {
            $errorsLog->iterations = $lastError->iterations + 1;
        }

        // file_formats can be OK, NOT OK, N/A (if no check file)
        $errorsLog->file_formats = $file_formats;

        $errorsLog->portfolio_name = $report->portfolio->portfolio_name;
        $errorsLog->period = $report->period_year . $report->period_quarter;
        $errorsLog->mandate = $report->portfolio->mandate;
        $errorsLog->beneficiary_name = $report->portfolio->beneficiary_name;


        return $this->save($errorsLog);
    }

    /*
     * updateError
     * 	fill the table errors_log for FI responsivness
     */

    public function updateError($report, $update)
    {
        //exception for inclusion with template PDLR
        if ($this->inclusionIsPdlr($report)) {
            return;
        }
        $errorsLog = $this->newEmptyEntity();
        $errorsLog->portfolio_id = $report->portfolio_id;
        $errorsLog->report_id = $report->report_id;

        $lastError = $this->find('all', [
                    'conditions' => [
                        'ErrorsLog.portfolio_id' => $report->portfolio_id,
                        'ErrorsLog.report_id'    => $report->report_id
                    ],
                    'order'      => [
                        'ErrorsLog.iterations DESC'
                    ]
                ])->first();

        if (empty($lastError)) {
            $errorsLog->iterations = 0;
            $errorsLog->file_formats = 'N/A';
            $errorsLog->portfolio_name = $report->portfolio_name;
            $errorsLog->period = $report->period_year . $report->period_quarter;
            $errorsLog->mandate = $report->portfolio->mandate;
            $errorsLog->beneficiary_name = $report->portfolio->beneficiary_name;
        } else{
            $errorsLog = $lastError;
        }

        if (!empty($update['Report.responsiveness'])) {
            $errorsLog->fi_responsivness = $update['Report.responsiveness'];
        }
        if (!empty($update['Report.comments'])) {
            $errorsLog->comments = $update['Report.comments'];
        }
        $this->save($errorsLog);
    }

    /*
     * excellError
     * 	fill the table errors_log for excell import error
     */

    public function ExcellError($report_id, $errors)
    {
        error_log("calling error log : report " . $report_id . " : " . json_encode($errors));
        $state = in_array('NOK', $errors) ? 'NOK' : 'OK';
        // already some iteration ?       
        $total = $this->find()->where(['report_id' => $report_id])->count();

        $report_data = $this->find();
        $report_data->select([
                    'lastiterations'   => $total > 0 ? $report_data->func()->max('ErrorsLog.iterations') : 0,
                    'report_id'        => 'ErrorsLog.report_id',
                    'portfolio_id'     => 'ErrorsLog.portfolio_id',
                    'portfolio_name'   => 'Portfolio.portfolio_name',
                    'mandate'          => 'Portfolio.mandate',
                    'beneficiary_name' => 'Portfolio.beneficiary_name',
                    'period_year'      => 'Report.period_year',
                    'period_quarter'   => 'Report.period_quarter',
                ])->innerJoinWith('Portfolio')
                ->innerJoinWith('Report')
                ->where(['ErrorsLog.report_id' => $report_id])
                ->group('ErrorsLog.report_id');
        $log_data = $report_data->first();
        if (empty($log_data)) {
            $report = $this->Report->find()->where(['report_id' => $report_id])->first();
            $portfolio = $this->Portfolio->find()->where(['portfolio_id' => $report->portfolio_id])->first();
            $log_data = $this->newEmptyEntity();
            $log_data->portfolio_id = $report->portfolio_id;
            $log_data->portfolio_name = $portfolio->portfolio_name;
            $log_data->mandate = $portfolio->mandate;
            $log_data->beneficiary_name = $portfolio->beneficiary_name;
            $log_data->period = $report->period_year . $report->period_quarter;
            $log_data->report_id = $report_id;
        }

        $iteration = (int) $log_data->lastiterations + 1;

        $values_error_log = new \Damsv2\Model\Entity\ErrorsLog([
            'portfolio_id'     => $log_data->portfolio_id,
            'portfolio_name'   => $log_data->portfolio_name,
            'mandate'          => $log_data->mandate,
            'beneficiary_name' => $log_data->beneficiary_name,
            'period'           => $log_data->period_year . $log_data->period_quarter,
            'report_id'        => $log_data->report_id,
            'file_formats'     => $state,
            'iterations'       => $iteration,
        ]);

        $error_log = $this->save($values_error_log);
        if (!empty($error_log)) {
            $error_id = $error_log->error_id;
            $connection = ConnectionManager::get('default');
            foreach ($errors as $sheet => $error) {
                error_log("error_log_detailed : " . $sheet . " : " . json_encode($error));
                $connection->insert('errors_log_detailed', [
                    'error_id'     => $error_id,
                    'sheet'        => $sheet,
                    'file_formats' => $error
                ]);
            }
        } else {
            error_log("could not save to error log : " . json_encode($values_error_log));
        }
    }

}
