<?php
/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * @var \Cake\Routing\RouteBuilder $routes
 */
use Cake\Core\Configure;
use Cake\Routing\RouteBuilder;
use CakeDC\Users\Utility\UsersUrl;

$routes->connect('/users/', [
    'plugin' => 'UserMgmt',
    'controller' => 'User',
    'action' => 'index'
]);

$routes->connect('/logout/', [
    'plugin' => 'UserMgmt',
    'controller' => 'Sso',
    'action' => 'logout'
]);
