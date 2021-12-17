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
 * Profile Model
 *
 * @property \UserMgmt\Model\Table\UsersTable&\Cake\ORM\Association\BelongsToMany $Users
 * @property \UserMgmt\Model\Table\PermissionsTable&\Cake\ORM\Association\HasMany $Permissions
 * 
 * @method \CakeDC\Users\Model\Entity\Profile get($primaryKey, $options = [])
 * @method \CakeDC\Users\Model\Entity\Profile newEntity($data = null, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Profile[] newEntities(array $data, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Profile|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CakeDC\Users\Model\Entity\Profile|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CakeDC\Users\Model\Entity\Profile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Profile[] patchEntities($entities, array $data, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Profile findOrCreate($search, callable $callback = null, $options = [])
 */
class ProfilesTable extends Table
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

        $this->setTable('user_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Users', [
            'through' => 'UserMgmt\Model\Table\SubscriptionsTable',
            'bindingKey' => 'group_id'
        ]);
        $this->hasMany('Permissions', [
            'className' => 'UserMgmt\Model\Table\PermissionsTable',
            'foreignKey' => 'user_group_id',
            'joinType'   => 'INNER'
        ]);
    }


}
