<?php

error_reporting(-1);

try {

	$config = require __DIR__ . '/../private/common/config/config.php';
	$loader = new \Phalcon\Loader();
	
	/**
	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 */
	$di = new \Phalcon\DI\FactoryDefault();

	/**
	 * Registering the config
	 */
	$di->setShared('config', $config);	

	$loader->registerNamespaces(
		array(
			'##APP_COMMON_LIB_NAMESPACE##'				=> $config->application->libraryDir,
			'##APP_COMMON_LIB_NAMESPACE##\Controllers'	=> $config->application->controllersDir,
			'##APP_COMMON_LIB_NAMESPACE##\Plugins'		=> $config->application->pluginsDir,
			'##APP_COMMON_LIB_NAMESPACE##\Models'		=> $config->application->modelsDir
		)
	);
	
	$loader->register();
	
	/**
	 * Registering a router
	 */
	$di->set('router', require $config->application->configDir . '/routes.php');
	
	/**
	 * Main logger file
	 */
	$logger = new \Phalcon\Logger\Adapter\File($config->application->logDir . date('Y-m-d') . '.log');
	$di->setShared('logger', $logger);
	
	/**
	 * Event Manager
	 */
	$eventsManager = new \Phalcon\Events\Manager();

	// Register for database events
	$eventsManager->attach('db', function($event, $connection) use($logger) {
		if ($event->getType() == 'beforeQuery') {
			$logger->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);
		}
	});	

	$di->setShared('eventsManager', $eventsManager);
	
	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	$di->set('url', function() use ($config) {
		$url = new \Phalcon\Mvc\Url();
		$url->setBaseUri($config->application->baseUri);
		return $url;
	});

	/**
	 * We register the events manager
	 */
	$di->set('dispatcher', function() use ($di) {

		$eventsManager = $di->getShared('eventsManager');
		// $security = new \##APP_COMMON_LIB_NAMESPACE##\Plugins\Security($di);

		/**
		 * We listen for events in the dispatcher using the Security plugin
         */
		// $eventsManager->attach('dispatch', $security);

		$dispatcher = new \Phalcon\Mvc\Dispatcher();
		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	});
	
	/**
	 * If the configuration specify the use of metadata adapter use it or use memory otherwise
	 */
	$di->set('modelsMetadata', function() use ($config) {
		if( isset($config->models->metadata) ) {
			$metaDataConfig = $config->models->metadata;
			$metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\' . $metaDataConfig->adapter;
			return new $metadataAdapter();
		} else {
			return new \Phalcon\Mvc\Model\Metadata\Memory();
		}
	});
	
	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function() {
		$session = new \Phalcon\Session\Adapter\Files();
		$session->start();
		return $session;
	});

	//Set the views cache service
	$di->set('viewCache', function() use ($config) {
		
		//Cache data for one day by default
		$frontCache = new \Phalcon\Cache\Frontend\Output(array(
			"lifetime" => $config->application->cacheTime
		));

		// APC Cache settings
		$cache = new Phalcon\Cache\Backend\Apc($frontCache);
		
		/* File backend settings
		$fileCache = new \Phalcon\Cache\Backend\File($frontCache, array(
			"cacheDir" => $config->application->cacheDir,
		));
		*/

		return $cache;
	});

	$di->setShared('modelsManager', function() {
		return new \Phalcon\Mvc\Model\Manager();
	});

	/**
	 * Set output modes
	 */
	\Phalcon\Tag::setDoctype(\Phalcon\Tag::HTML5);

	/**
	 * Error handler
	 */
	set_error_handler(function($errno, $errstr, $errfile, $errline) use ($di, $logger)
	{
		if (!(error_reporting() & $errno)) {
			return;
		}

		$di->getFlash()->error($errstr);
		$logger->log("$errstr $errfile: $errline", \Phalcon\Logger::ERROR);

		return true;
	});

	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);

	/**
	 * Register application modules
	 */
	$application->registerModules(require $config->application->configDir . '/modules.php');

	/**
	 * Handle the request
	 */
	echo $application->handle()->getContent();

} catch (\Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
} catch (Exception $e) {
  echo $e->getMessage();
}
