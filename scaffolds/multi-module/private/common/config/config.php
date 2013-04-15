<?php

$config = array(
	'database' => array(
		'adapter'  		=> '##APP_DATABASE_ADAPTER##',
		'host'     		=> '##APP_DATABASE_HOST##',
		'username' 		=> '##APP_DATABASE_USER##',
		'password' 		=> '##APP_DATABASE_PASSWORD##',
		'dbname'     	=> '##APP_DATABASE_NAME##',
		'persistent' 	=> ##APP_DATABASE_PERSISTENT##,
		'charset'   	=> 'utf8'
	),
	'application' => array(
		'baseDir'     	=> __DIR__ . '/../../../',
		'cacheDir'     	=> __DIR__ . '/../../var/cache/',
		'cacheTime'    	=> 86400,
		'logDir'     	=> __DIR__ . '/../../var/log/',
		'configDir'		=> __DIR__,
		'modelsDir'     => __DIR__ . '/../models/',
		'pluginsDir'    => __DIR__ . '/../plugins/',
		'libraryDir'    => __DIR__ . '/../lib/',
		'controllersDir'    => __DIR__ . '/../lib/controllers/',
		'baseUri'       => '##APP_BASE_URI##'
	),
	'models' => array(
		'metadata' => array(
			'adapter' => '##APP_METADATA_ADAPTER##'
		)
	)
);

return new \Phalcon\Config($config);
