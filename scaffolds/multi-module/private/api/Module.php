<?php

namespace ##APP_NAMESPACE##\##APP_API_MODULE_NAMESPACE##;

class Module
{

	public function registerAutoloaders()
	{

		$loader = new \Phalcon\Loader();

		$loader->registerNamespaces(array(
			'##APP_NAMESPACE##\##APP_API_MODULE_NAMESPACE##\Controllers'		=> __DIR__ . '/controllers/'
		));

		$loader->register();
	}

	public function registerServices($di)
	{


		/**
		 * Setting up the view component
		 */
		$di->set('view', function() {

			$view = new \Phalcon\Mvc\View();

			// Disable several levels
		    $view->disableLevel(array(
		        \Phalcon\Mvc\View::LEVEL_LAYOUT => true,
		        \Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT => true,
		    	\Phalcon\Mvc\View::LEVEL_AFTER_TEMPLATE => true,
		    	\Phalcon\Mvc\View::LEVEL_BEFORE_TEMPLATE => true,
		    	\Phalcon\Mvc\View::LEVEL_ACTION_VIEW => true
		    ));

		    $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);

		    return $view;
		});
		
		/**
		 * Read configuration and set database connection based on the
		 * parameters defined in the configuration file
		 */
		$config = $di->getShared('config');

		$di->set('db', function() use ($config, $di) {

			$db = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
				"host" 		=> $config->database->host,
				"username"	=> $config->database->username,
				"password"	=> $config->database->password,
				"dbname"	=> $config->database->name
			));
			
			$db->setEventsManager($di->getShared('eventsManager'));
			return $db;
		});
	}

}
