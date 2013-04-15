<?php

/**
 *
 * Application routing setup
 *
 * All requests that do not target "/##APP_VIEW_MODULE##/" or "/##APP_API_MODULE##/"
 * go to the index controller.
 *
 */

$router = new \Phalcon\Mvc\Router();

/**
 * Index routes
 *
 * All requests go to the index controller.
 */
$router->add ( '/(.*)', array (
	'module'		=> '##APP_VIEW_MODULE##',
	'namespace'		=> '##APP_NAMESPACE##\##APP_VIEW_MODULE_NAMESPACE##\Controllers\\',
	'controller'	=> 'index',
	'action'		=> 'index'
));

/**
 * Add module specific routes
 * ##APP_VIEW_MODULE_NAMESPACE## routes
 *
 * All "/##APP_VIEW_MODULE_##/" requests are routed to the specific Application
 * controller.
 */
$router->add ( '/##APP_VIEW_MODULE##/:controller', array (
	'module'		=> '##APP_VIEW_MODULE##',
	'namespace'		=> '##APP_NAMESPACE##\##APP_VIEW_MODULE_NAMESPACE##\Controllers\\',
	'controller'	=> 1,
	'action'		=> 'index'
));

$router->add ( '/##APP_VIEW_MODULE##/:controller/:action/:params(/*)', array (
	'module'		=> '##APP_VIEW_MODULE##',
	'namespace'		=> '##APP_NAMESPACE##\##APP_VIEW_MODULE_NAMESPACE##\Controllers\\',
	'controller'	=> 1,
	'action'		=> 2,
	'params'		=> 3
));

/**
 * Add module specific routes:
 * ##APP_API_MODULE_NAMESPACE## routes
 *
 * All "/##APP_API_MODULE##/" requests are routed to the specific API controller.
 */
$api = new Phalcon\Mvc\Router\Group(array(
	'module'			=> '##APP_API_MODULE##',
	'controller'		=> 'index'
));
  
$api->setPrefix('/##APP_API_MODULE##');

$api->addGet('/:controller/:params', array(
	'controller' => 1,
	'action' => 'get',
	'params'	=> 2
));

$api->addPost('/:controller/:params', array(
	'controller' => 1,
	'action' => 'post',
	'params'	=> 2
));

//Add delete route
$api->addDelete('/:controller/:params', array(
	'controller' => 1,
	'action' => 'delete',
	'params'	=> 2
));

//Add put route
$api->addPut('/:controller/:params', array(
	'controller' => 1,
	'action' => 'put',
	'params'	=> 2
));

$router->mount($api); 

return $router;
