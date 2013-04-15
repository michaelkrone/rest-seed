<?php

namespace ##APP_NAMESPACE##\##APP_VIEW_MODULE_NAMESPACE##;

class Module
{

	public function registerAutoloaders()
	{

		$loader = new \Phalcon\Loader();

		$loader->registerNamespaces(array(
			'##APP_NAMESPACE##\##APP_VIEW_MODULE_NAMESPACE##\Controllers'	=> __DIR__ . '/controllers/'
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
			$view->setViewsDir(__DIR__ . '/views/');

			return $view;
		});
	}
}
