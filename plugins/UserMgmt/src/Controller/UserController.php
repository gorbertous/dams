<?php
declare(strict_types=1);

namespace UserMgmt\Controller;

use UserMgmt\Controller\AppController;
use Cake\ORM\TableLocator;
use Cake\Collection\Collection;
use Cake\I18n\I18n;

/**
 * User Controller
 *
 * @method \UserMgmt\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserController extends AppController
{
	public $paginate = [
        'limit'          => 25,
    ];
	public function initialize(): void
    {
        parent::initialize();
		I18n::setLocale('fr_FR');
       // $this->Authentication->allowUnauthenticated(['login', 'index']);

    }

	public function noaccess()
	{

	}

	
	// url: '/users'
	public function index()
	{
		//profiles having access: admin, user manager
		$POST = $this->request->getData('User.search');
		$conditions = array();
		if (!empty($POST))
		{
			$conditions['OR'] = array();
			$conditions['OR']['Users.username LIKE '] = '%'.$POST.'%';
			$conditions['OR']['Users.first_name LIKE '] = '%'.$POST.'%';
			$conditions['OR']['Users.last_name LIKE '] = '%'.$POST.'%';
		}

		$User = $this->getTableLocator()->get('UserMgmt.Users');
		$query = $User->find('all', [
            'contain'    => ['Profiles'],
            'conditions' => [$conditions]
        ]);
        $users = $this->paginate($query);

        $this->set(compact('users'));
	}


	// url: '/user-mgmt/user/view-user/'
	public function viewUser($userId=null)
	{
		if (!empty($userId))
		{
			$User = $this->getTableLocator()->get('UserMgmt.Users');
			$POST = $this->request->getData('User');
			$Subscriptions = $this->getTableLocator()->get('UserMgmt.Subscriptions');

			$user = $User->find('all', ['contain' => ['Profiles']])->where(['id' => $userId])->first();
			$user_array = $user->toArray();
			if (!empty($POST))
			{
				$user->first_name = $POST['first_name'];
				$user->last_name = $POST['last_name'];
				$user->username = $POST['username'];
				$user = $User->save($user);
				
				if (!empty($POST['profiles']))
				{
					$block_change_profile = false;
					$current_user_is_admin = false;
					$current_user = $this->Authentication->getIdentity();
					if (!empty($current_user))
					{
						$User = $this->getTableLocator()->get('UserMgmt.Users');
						$query = $User->find('all', [
							'contain'    => ['Profiles'],
							'conditions' => ['Users.id' => $current_user->get('id')]
						])->first();
						foreach($query->profiles as $profil)
						{
							if ($profil->id == 1)
							{
								$current_user_is_admin = true;
							}
						}
					}
					if (($userId == $current_user->get('id')) && (!$current_user_is_admin))
					{
						$this->Flash->error('User Manager members cannot edit themselves.');
                        $block_change_profile = true;
					}
					if (in_array(1, $POST['profiles']) && !$current_user_is_admin)
					{
						// profile admin can only be assigned by another admin
						$this->Flash->error('Admin profile can only be assigned by another administrator.');
						$block_change_profile = true;
					}
					if (! $block_change_profile)
					{
						$subs_to_delete = $Subscriptions->find()->where(['user_id' => $user_array['id']])->all();
						foreach($subs_to_delete as $sub_to_delete)
						{
							$Subscriptions->delete($sub_to_delete);
						}

						foreach($POST['profiles'] as $new_profile)
						{
							$profile_user = $Subscriptions->newEntity(['user_id' => $user->id, 'group_id' => $new_profile]);
							$Subscriptions->save($profile_user);
						}
					}
				}
			}

			$this->set('user', $user);

			//get profiles
			$Profile = $this->getTableLocator()->get('UserMgmt.Profiles');
			$subscription = $Subscriptions->find()->where(['user_id' => $user_array['id']])->all();
			$group_ids = array();
			$profiles_selected = array();
			foreach($subscription as $id)
			{
				$group_ids[] = $id->group_id;
			}
			$profiles_all = $Profile->find()->select(['id', 'name'])->all();
			$profiles_combined = (new Collection($profiles_all))->combine('id', 'name');
			if (!empty($group_ids))
			{
				$profiles = $Profile->find()->where(['id IN' => $group_ids])->all();
				foreach($profiles as $prof)
				{
					$profiles_selected[] = $prof->id;
				}
			}
			$this->set('profiles_selected', $profiles_selected);
			$this->set('profiles_all', $profiles_combined);
		}
		else
		{
			$this->redirect('/users');
		}
	}
	// url: '/profile'
	public function profile()
	{
		$user = $this->Authentication->getIdentity();

		if (!empty($user))
		{
			$User = $this->getTableLocator()->get('UserMgmt.Users');
			$query = $User->find('all', [
				'contain'    => ['Profiles'],
				'conditions' => ['Users.id' => $user->get('id')]
			])->first();
			//debug($query);
			$this->set('user', $user);
			//get profiles
			//$profiles = $this->getRequest()->getSession()->read('user_profiles');

			$this->set('profiles', $query->profiles);
		}
	}
	// url: '/user-mgmt/user/groups'
	public function groups()
	{
		$Profile = $this->getTableLocator()->get('UserMgmt.Profiles');
		$query = $Profile->find('all', [
            'contain'    => null,
        ]);
		$profiles = $this->paginate($query);
		$this->set('profiles', $profiles);
	}
	// url: '/user-mgmt/user/edit-group'
	public function editGroup($group_id = null)
	{
		if (!empty($group_id))
		{
			$Profile = $this->getTableLocator()->get('UserMgmt.Profiles');
			$group_data = $Profile->find()->where(['id' => $group_id])->first();

			$POST = $this->request->getData('Profile');
			if (!empty($POST))
			{
				$group_data->name = $POST['name'];
				$group_data->alias_name = $POST['alias_name'];
				$group_data = $Profile->save($group_data);
			}

			$this->set('profile', $group_data);
		}
	}
	// url: '/user-mgmt/user/permissions'
	public function permissions()
	{
		$Profile = $this->getTableLocator()->get('UserMgmt.Profiles');
		$group_data = $Profile->find()->where(['id' => $group_id])->first();

		$POST = $this->request->getData('Profile');
		if (!empty($POST))
		{
			$group_data->name = $POST['name'];
			$group_data->alias_name = $POST['alias_name'];
			$group_data = $Profile->save($group_data);
		}

		$this->set('profile', $group_data);
	}
}
