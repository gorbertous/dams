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
 * Subscriptions Model
 *
 * @property \UserMgmt\Model\Table\ProfilesTable&\Cake\ORM\Association\BelongsTo $Profiles
 * @property \UserMgmt\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * 
 * @method \CakeDC\Users\Model\Entity\Subscription get($primaryKey, $options = [])
 * @method \CakeDC\Users\Model\Entity\Subscription newEntity($data = null, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Subscription[] newEntities(array $data, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Subscription|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CakeDC\Users\Model\Entity\Subscription|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CakeDC\Users\Model\Entity\Subscription patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Subscription[] patchEntities($entities, array $data, array $options = [])
 * @method \CakeDC\Users\Model\Entity\Subscription findOrCreate($search, callable $callback = null, $options = [])
 */
class SubscriptionsTable extends Table
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

        $this->setTable('user_group_subscriptions');
        $this->setDisplayField('group_id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', [
            'className' => 'UserMgmt\Model\Table\UsersTable',
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Profiles', [
            'className' => 'UserMgmt\Model\Table\ProfilesTable',
            'foreignKey' => 'group_id'
        ]);
    }
}
