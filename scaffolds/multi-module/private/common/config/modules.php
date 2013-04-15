<?php
/**
 * Module definitions
 *
 * @var array
 */
return array(

	'##APP_API_MODULE##' => array(
		'className' => '##APP_NAMESPACE##\##APP_API_MODULE_NAMESPACE##\Module',
		'path' => '../private/##APP_API_MODULE##/Module.php'
	),

	'##APP_VIEW_MODULE##' => array(
			'className' => '##APP_NAMESPACE##\##APP_VIEW_MODULE_NAMESPACE##\Module',
			'path' => '../private/##APP_VIEW_MODULE##/Module.php'
	)

);