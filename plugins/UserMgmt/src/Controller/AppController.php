<?php

declare(strict_types=1);

namespace UserMgmt\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }
	/*
    public function beforeFilter(EventInterface $event)
    {
        $this->buildPermissons();
        $this->checkPermissions();
    }

    protected function checkPermissions()
    {
        $ident = $this->userIdentity();
        if (!in_array($this->getName(), AppController::$excludedControllers)) {
            if (in_array($this->getName(), AppController::$simplifiedCheckControllers)) {
                if ($ident == null ||
                        ($this->request->is(['get']) && !$this->perm->hasRead()) ||
                        ($this->request->is(['patch', 'post', 'put', 'delete']) && !$this->perm->hasWrite())) {
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
                    $this->Flash->error(__('Access Denied.'));
                    $this->setAction("deny");
                }
            }
        }
    }

    protected function buildPermissons()
    {
        $this->viewBuilder()->setLayout('dams');
        $ident = $this->userIdentity();

        if ($ident != null) {
            $this->perm = new PermissionsHelper($this->getName(),
                    $this->request->getParam('action'),
                    UsersTable::getEffectivePermissions((int) $ident->id)['UserMgmt']);
            $this->set('perm', $this->perm);
        } else {
            $this->setAction("deny");
        }
    }
*/
    public function deny()
    {
        $this->redirect(['controller' => 'user', 'action' => 'index']);
    }

    protected function isExcludedController() 
    {
        return true;
    }

}
