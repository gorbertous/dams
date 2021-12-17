<?php
declare(strict_types=1);

/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace UserMgmt\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Permissions Model
 *
 * @property \UserMgmt\Model\Table\ProfilesTable&\Cake\ORM\Association\BelongsTo $Profile
 * 
 * @method \UserMgmt\Model\Entity\Permission get($primaryKey, $options = [])
 * @method \UserMgmt\Model\Entity\Permission newEntity($data = null, array $options = [])
 * @method \UserMgmt\Model\Entity\Permission[] newEntities(array $data, array $options = [])
 * @method \UserMgmt\Model\Entity\Permission|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \UserMgmt\Model\Entity\Permission|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \UserMgmt\Model\Entity\Permission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \UserMgmt\Model\Entity\Permission[] patchEntities($entities, array $data, array $options = [])
 * @method \UserMgmt\Model\Entity\Permission findOrCreate($search, callable $callback = null, $options = [])
 */
class PermissionsTable extends Table
{
	
	public static function defaultConnectionName(): string {
        return 'eif';
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setEntityClass('UserMgmt\Model\Entity\Permission');
        
        $this->setTable('user_group_permissions');
        $this->setDisplayField('plugin');

        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Profile', [
            'className' => 'UserMgmt\Model\Table\ProfilesTable',
            'foreignKey' => 'user_group_id'
        ]);
        // $this->belongsToMany('Users', [
        //     'through' => 'Subscriptions',
        //     'foreignKey' => 'user_group_id'
        // ]);
    }


}
