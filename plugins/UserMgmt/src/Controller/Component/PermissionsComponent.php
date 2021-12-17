<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace UserMgmt\Controller\Component;

use ArrayAccess;

use Cake\Controller\Component;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Exception;
use RuntimeException;

/**
 * Controller Component for interacting with Authentication.
 */
class PermissionsComponent extends Component
{
    use EventDispatcherTrait;

    /**
     * Configuration options
     *
     * - `logoutRedirect` - The route/URL to direct users to after logout()
     * - `requireIdentity` - By default AuthenticationComponent will require an
     *   identity to be present whenever it is active. You can set the option to
     *   false to disable that behavior. See allowUnauthenticated() as well.
     * - `unauthenticatedMessage` - Error message to use when `UnauthenticatedException` is thrown.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'logoutRedirect' => false,
        'requireIdentity' => true,
        'identityAttribute' => 'identity',
        'identityCheckEvent' => 'Controller.startup',
        'unauthenticatedMessage' => null,
    ];

    /**
     * List of actions that don't require authentication.
     *
     * @var string[]
     */
    protected $unauthenticatedActions = [];

    /**
     * Authentication service instance.
     *
     * @var \Authentication\AuthenticationServiceInterface|null
     */
    protected $_authentication;

    /**
     * Initialize component.
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize(array $config): void
    {
        $controller = $this->getController();
        $this->setEventManager($controller->getEventManager());

		$profiles = $this->getController()->getRequest()->getSession()->read('user_profiles');
		$group_ids = array();
		foreach($profiles as $prof)
		{
			$group_ids[] = $prof['id'];
		}
		$plugin = $controller->getPlugin();
		
		$action = $controller->getAction;
		$controller = $controller->getName();
		//debug($controller);
		$Permissions = $this->getController()->getTableLocator()->get('UserMgmt.Permissions');
	
		$perms = $Permissions->find()->where(['user_group_id IN ' => $group_ids, 'plugin' => $plugin, 'controller' => $controller, 'action' => $action])->all();
		$access = false;
		foreach($perms as $p)
		{
			if ($p->allowed)
			{
				$access = true;
			}
		}
		if (!$access)
		{
			//redirect to no access screen
			$this->redirect('/user-mgmt/user/noaccess');
		}
    }

    /**
     * Triggers the Authentication.afterIdentify event for non stateless adapters that are not persistent either
     *
     * @return void
     */
    public function beforeFilter(): void
    {

		//return $access;
    }

    /**
     * Start up event handler
     *
     * @return void
     * @throws \Exception when request is missing or has an invalid AuthenticationService
     * @throws \Authentication\Authenticator\UnauthenticatedException when requireIdentity is true and request is missing an identity
     */
    public function startup(): void
    {
        /*if ($this->getConfig('identityCheckEvent') === 'Controller.startup') {
            $this->doIdentityCheck();
        }*/
		// check if user has profiles that has access to the requested screen
    }
    public function shutdown(): void
    {
        /*if ($this->getConfig('identityCheckEvent') === 'Controller.startup') {
            $this->doIdentityCheck();
        }*/
		// check if user has profiles that has access to the requested screen
    }
    public function beforeRedirect(Controller $controller, $url, $status=null, $exit=true): void
    {
		//return false;
    }

}
