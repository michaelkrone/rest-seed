<?php

namespace ##APP_COMMON_LIB_NAMESPACE##\Plugins;

class Security extends \Phalcon\Mvc\User\Plugin {

	/**
	 * @var \Phalcon\Acl\Adapter\Memory
	 */
	protected $_acl;
	
	/**
	 * @param $dependencyInjector \Phalcon\DI
	 */
	public function __construct($dependencyInjector) {
		$this->_dependencyInjector = $dependencyInjector;
	}

	public function getAcl() {
		if (!$this->_acl) {

			$acl = new \Phalcon\Acl\Adapter\Memory();

			$acl->setDefaultAction(\Phalcon\Acl::DENY);

			//Register roles
			$roles = array(
				'Admins' => new \Phalcon\Acl\Role('Admins'),
				'Users' => new \Phalcon\Acl\Role('Users'),
				'Nobody' => new \Phalcon\Acl\Role('Nobody')
			);
			
			foreach($roles as $role){
				$acl->addRole($role);
			}

			//Private area resources
			$privateResources = array(
			);

			foreach($privateResources as $resource => $actions) {
				$acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
			}

			//Public area resources
			$publicResources = array(
				'index' => array('index')
			);
			
			foreach($publicResources as $resource => $actions){
				$acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);
			}

			//Grant access to public areas to both users and guests
			foreach($roles as $role){
				foreach($publicResources as $resource => $actions){
					$acl->allow($role->getName(), $resource, '*');
				}
			}

			//Grant acess to private area to role Admins
			foreach($privateResources as $resource => $actions){
				foreach($actions as $action){
					$acl->allow('Admins', $resource, $action);
				}
			}

			$this->_acl = $acl;
		}
		return $this->_acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 */
	public function beforeDispatch(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher) {
		$user = $this->session->get('auth');
		
		if (!$user){
			$role = 'Nobody';
		} else {
			$role = $user['role'];
		}

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();
		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action);	
		
		if ($allowed === \Phalcon\Acl::ALLOW) {		
			return true;
		}

		$response = new \Phalcon\Http\Response();
		$response->setStatusCode("401", "Unauthorized Request");
		$response->sendHeaders();
		return false;	
	}

}
