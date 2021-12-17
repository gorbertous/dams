<?php

declare(strict_types=1);

namespace Treasury\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
//use cake\Event\EventInterface;
//use Cake\Datasource\ConnectionManager;
//use DateTime;
//use Cake\Routing\Router;
//use App\Lib\PermissionsHelper;
//use Authentication\Identity;
//use UserMgmt\Model\Table\UsersTable;
use App\Controller\AppController as BaseController;

class AppController extends BaseController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        if (!Configure::read('Development')) {
            //Create a development.php and set Development to disable
            $this->loadComponent('Authentication.Authentication');
        }

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    protected function isExcludedController()
    {
        return true;
    }
}
