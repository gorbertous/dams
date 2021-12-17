<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Daily Model
 *
 * @method \App\Model\Entity\Daily newEmptyEntity()
 * @method \App\Model\Entity\Daily newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Daily[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Daily get($primaryKey, $options = [])
 * @method \App\Model\Entity\Daily findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Daily patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Daily[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Daily|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Daily saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Daily[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ReportanalyticslogTable extends Table
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

        $this->setTable('report_analytics_log');
        $this->setDisplayField('report');
        $this->setPrimaryKey('log_id');
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'eif';
    }
}
