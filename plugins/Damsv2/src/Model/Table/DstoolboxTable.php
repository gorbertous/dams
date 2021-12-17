<?php
declare(strict_types=1);

namespace Damsv2\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dstoolbox Model
 
 * @method \App\Model\Entity\Dstoolbox newEmptyEntity()
 * @method \App\Model\Entity\Dstoolbox newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Dstoolbox[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dstoolbox get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dstoolbox findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Dstoolbox patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dstoolbox[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dstoolbox|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dstoolbox saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dstoolbox[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dstoolbox[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dstoolbox[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dstoolbox[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DstoolboxTable extends Table
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

        $this->setTable('dstoolbox');
        $this->setDisplayField('name');
        $this->setPrimaryKey('dstoolbox_id');
        
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'creation_date' => 'new',
                    'modification_date' => 'always'
                ]
            ]
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
            ->integer('dstoolbox_id')
            ->allowEmptyString('dstoolbox_id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('filename')
            ->maxLength('filename', 100)
            ->allowEmptyFile('filename');
        
        $validator
            ->allowEmptyFile('filename_temp');

        $validator
            ->scalar('BO_url')
            ->maxLength('BO_url', 100)
            ->allowEmptyString('BO_url');


        return $validator;
    }

}
