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

namespace Damsv2\Controller;

use cake\Event\EventInterface;
use Cake\Datasource\ConnectionManager;
use DateTime;
use Cake\Routing\Router;
use App\Lib\PermissionsHelper;
//use App\Lib\Helpers;
//use Authentication\Identity;
//use UserMgmt\Model\Table\UsersTable;
use App\Controller\AppController as BaseController;

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
class AppController extends BaseController
{
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

        // $this->loadComponent('RequestHandler');
        // $this->loadComponent('Flash');
        // if (!Configure::read('Development')) {
        //     //Create a development.php and set Development to disable
        //     $this->loadComponent('Authentication.Authentication');
        // }

        // if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 600000000);//for dom parsing
        // /*
        //  * Enable the following component for recommended CakePHP form protection settings.
        //  * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
        //  */
        // //$this->loadComponent('FormProtection');
    }

    protected function buildPermissons()
    {
        $this->viewBuilder()->setLayout('dams');
        parent::buildPermissons();
        // $ident = $this->userIdentity();

        // if ($ident != null) {
        //     $this->perm = new PermissionsHelper($this->getPlugin(), $this->getName(),
        //             $this->request->getParam('action'),
        //             UsersTable::getEffectivePermissions((int) $ident->id));
        //     $this->set('perm', $this->perm);
        // } else {

        // }
    }


    public function beforeRender(EventInterface $event)
    {
        if ($this->request->is('ajax')) {
            //set view to Ajax
            $this->viewBuilder()->setLayout('ajax');
        }
    }

    public function logDams($msg, $type = LOG_ERR, $category = 'DAMS', $scope = NULL)
    {
        $eifconnection = ConnectionManager::get('eif');
        //$user = CakeSession::read('UserAuth.User.username');
        $user = $this->userIdentity()->get('username');
        $eifconnection->insert('log_entries_dams', [
            'type'     => 'Dams',
            'message'  => addslashes($msg),
            'category' => $category,
            'datetime' => new DateTime('now'),
            'user'     => $user,
            'url'      => Router::url()
        ], ['datetime' => 'datetime']);
    }

    private function scan_param($data = null)
    {
        if (is_array($data)) {
            foreach ($data as $data_element) {
                $this->scan_param($data_element);
            }
        } else {
            $this->validate_param('string', $data); //default check as string
        }
    }

    function validationerror()
    {
    }

    public function blackhole($type)
    {
        @$this->validate_param('string', $type);
        // gestions des erreurs.
        error_log('blackhole : reason ' . json_encode($type));
        $this->redirect(array('action' => '/myprofile'));
        exit();
    }

    public function validate_param($type = 'string', $value = null, $possible_values = [])
    {
        if ($value == null) {
            return true; //allow null
        }
        $result = true;
        switch ($type) {
            case 'log':
            case 'string':
                if (!is_string($value)) {
                    $value = json_encode($value);
                }
                $value = trim($value);
                if (empty($value)) { //allow empty
                    $result = true;
                }
                $check2 = Normalizer::normalize($value);
                if ($check2 !== $value) {
                    $result = false;
                }
                /* $check3 = filter_var ( $value, FILTER_SANITIZE_STRING);//prevent single quote
                  if ($check3 !== $value)
                  {
                  $result = false;
                  } */

                if (strpos($value, '<') !== false) {
                    $result = false;
                }
                if (strpos($value, '>') !== false) {
                    $result = false;
                }
                /* if (strpos($value, '"'))
                  {
                  $result = false;
                  } */
                /* if (strpos($value, '/'))// will prevent login
                  {
                  $result = false;
                  } */
                $matches = [];
                preg_match('/<[script]{4,}/i', $value, $matches);
                if (!empty($matches)) {
                    $result = false;
                }
                if (strpos($value, '$') !== false) {
                    $result = false;
                }
                if (strpos($value, '&') !== false) {
                    //$result = false;// TODO find a fix
                }
                if (strpos($value, '\\') !== false) {
                    $result = false;
                }
                break;

            case 'int':
                if (gettype($value) == 'string') {
                    $value = str_replace(',', '', $value);
                }
                $result = (filter_var($value, FILTER_VALIDATE_INT) !== false);
                break;

            case 'decimal':
                if (gettype($value) == 'string') {
                    $value = str_replace(',', '', $value);
                }
                $result = (filter_var($value, FILTER_VALIDATE_FLOAT) !== false);
                break;
            case 'bool':
                $result = (filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== false);
                if (!$result) {
                    $result = (($result == 0) | ($result == 1));
                    if (!$result) {
                        $result = (($result == "1") | ($result == "0"));
                    }
                }
                break;

            case 'date':
                if (!DateTime::createFromFormat('d/m/Y', $value)) {
                    if (!DateTime::createFromFormat('Y-m-d', $value)) {
                        $result = false;
                    }
                }
                break;

            case 'array':
                $result = is_array($value);
                break;

            default:
                error_log("validate_param: wrong type provided : " . $type);
                break;
        }

        if (!empty($possible_values)) {
            $result = in_array($value, $possible_values, true);
        }

        if (!$result) {
            error_log("validate_param : wrong type for parameter " . $type . "  => " . json_encode($value));
            $this->Flash->error("The value cannot be saved in the database due to illegal characters.");
            $this->redirect('/validationerror');
            exit();
        }

        return $result;
    }

    protected function isExcludedController()
    {
        return true;
    }
}
