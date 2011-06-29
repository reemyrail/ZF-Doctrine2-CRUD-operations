<?php

class Federico_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    private $_acl = null;
    private $_auth = null;
    const DEFAULT_ROLE = 'guest';

    public function __construct($auth) {
        $this->_auth = $auth;
        // agrego roles
        $this->_acl = new Zend_Acl();
        $this->_acl->addRole(new Zend_Acl_Role(self::DEFAULT_ROLE));
        $this->_acl->addRole(new Zend_Acl_Role('user'), self::DEFAULT_ROLE);
        $this->_acl->addRole(new Zend_Acl_Role('admin'), 'user');
        // agrego recursos a controlar
        $this->_acl->addResource(new Zend_Acl_Resource('index'));
        $this->_acl->addResource(new Zend_Acl_Resource('users'));
        $this->_acl->addResource(new Zend_Acl_Resource('about'));
        $this->_acl->addResource(new Zend_Acl_Resource('federico'));
        $this->_acl->addResource(new Zend_Acl_Resource('admin'));
        // defino reglas de control
        $this->_acl->allow('guest', 'index');
        $this->_acl->allow('guest', 'about');
        $this->_acl->deny('guest', 'federico');
        $this->_acl->deny('guest', 'users');
        $this->_acl->deny('user', 'federico');
        $this->_acl->allow('user', 'users', array('index')); 
        $this->_acl->allow('admin', 'users');
        $this->_acl->allow('admin', 'federico');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if ($this->_auth->hasIdentity()) {
            // user is logged in and we can get role
            $role = $this->_auth->getStorage()->read()->getRole();
        } else {
            // guest
            $role = self::DEFAULT_ROLE;
        }
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        if ($this->_acl->has($controller)) {
            if (!$this->_acl->isAllowed($role, $controller, $action)) {
                $request->setActionName('error');
                $request->setControllerName('error');
            }
        }
    }
    
    public function getAcl()
    {
        return $this->_acl;
    }
}
