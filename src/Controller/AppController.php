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
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Controller\Controller;
use Cake\Http\Exception\NotFoundException;
use cake\Event\EventInterface;
use Authentication\Identity;
use UserMgmt\Model\Table\UsersTable;
use App\Lib\PermissionsHelper;

/**
 * Application Controller
 * 
 * @property UserMgmt\Model\Table\UsersTable $User
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    protected $excludedControllers = [
        'login', 'Sso', 
           ];
    protected $simplifiedCheckControllers = [];
    protected $perm = null;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        if (!Configure::read('Development')) {
            //Create a development.php and set Development to disable
            $this->loadComponent('Authentication.Authentication');
        }

		if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 600000000);//for dom parsing
        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    protected function buildPermissons()
    {
        $ident = $this->userIdentity();

        if ($ident != null) {
            $this->perm = new PermissionsHelper($this->getPlugin(), $this->getName(),
                    $this->request->getParam('action'),
                    UsersTable::getEffectivePermissions((int) $ident->id));
            $this->set('perm', $this->perm);
        } else {
            
        }
    }

    protected function isExcludedController() 
    {
        return in_array($this->getName(), $this->excludedControllers);
    }

    protected function isSimplifiedController() 
    {
        return in_array($this->getName(), $this->simplifiedCheckControllers);
    }

    protected function checkPermissions()
    {
        if ($this->plugin == null) return;
        $ident = $this->userIdentity();
        if (!$this->isExcludedController()) {
            if ($this->isSimplifiedController()) {
                if ($ident == null ||
                        ($this->request->is(['get']) && !$this->perm->hasRead()) ||
                        ($this->request->is(['patch', 'post', 'put', 'delete']) && !$this->perm->hasWrite())) {
                    error_log('access denied ' . $this->getName() . ' ' . $this->request->getParam('action') . ' ' . json_encode($ident));
                    $this->Flash->error(__('Access Denied.'));
                    $this->setAction("deny");
                }
            } else {
                if ($ident == null ||
                        ($this->request->is(['get']) && !$this->perm->hasRead()) ||
                        ($this->request->is(['post']) && !$this->perm->hasInsert()) ||
                        ($this->request->is(['patch', 'put']) && !$this->perm->hasUpdate()) ||
                        ($this->request->is(['delete']) && !$this->perm->hasDelete())
                ) {
                    error_log('access denied2 ' . $this->getName() . ' ' . $this->request->getParam('action') . ' ' . json_encode($ident));
                    $this->Flash->error(__('Access Denied.'));
                    $this->setAction("deny");
                }
            }
        }
    }

    public function deny()
    {
        $this->redirect(['controller' => 'Home', 'action' => 'home']);
    }

    public function beforeFilter(EventInterface $event)
    {
        $this->buildPermissons();
        $this->checkPermissions();
    }

    public function userIdentity()
    {
        if (isset($this->Authentication) && $this->Authentication != null) {
            return !empty($this->Authentication->getIdentity()) ? $this->Authentication->getIdentity() : null;
        } else {
            return new Identity(Configure::read('test_identity'));
        }
    }

    public function paginate($object = null, array $settings = []) {
        try {
            return parent::paginate($object, $settings);
        } catch (NotFoundException $e) {
            return $this->redirect(['?' => ['page' => 1]]);
        }
    }
}
