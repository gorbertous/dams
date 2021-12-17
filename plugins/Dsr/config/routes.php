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

$routes->connect('/dsr/', [
    'plugin' => 'Dsr',
    'controller' => 'Home',
    'action' => 'home'
]);


