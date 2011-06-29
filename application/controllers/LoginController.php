<?php

class LoginController extends Zend_Controller_Action
{
    protected $_entity;
    protected $_pass;
    
    public function init()
    {
        $this->em = Zend_Registry::get('em');
        $this->_entity = new Federico\Entity\User();
    }

    public function indexAction()
    {
        $form = new Form_Login;
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($this->_process($form->getValues())) {
					$role = Zend_Auth::getInstance()->getStorage()->read()->getRole();
                    // We're authenticated! Redirect to the home page depending on role
                    if($role === 'admin') {
                        $this->_helper->redirector('index', 'federico');
                    } else {
						$this->_helper->redirector('index', 'users');
					}	
                }
            }
        }
        $this->view->form = $form;
    }

    protected function _process($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['email']); 
        $adapter->setCredential($values['password']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $this->em->getRepository('Federico\Entity\User')->findOneByEmail($values['email']);
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    protected function _getAuthAdapter() 
    {    
        $authAdapter = new Federico_Auth_Adapter_Doctrine($this->em,
                            'Federico\Entity\User', 'email', 'password');
        return $authAdapter;
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index'); // back to login page
    }


}

