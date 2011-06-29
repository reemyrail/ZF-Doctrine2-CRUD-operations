<?php

class UsersController extends Zend_Controller_Action
{
    const PARAM_GET_ID = 'id';

    public function init()
    {
        $this->em = Zend_Registry::get('em');
    }

    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $id = $auth->getStorage()->read()->getId();
        $user = $this->em->getRepository('Federico\Entity\User')->findOneById($id);
        $languages = $user->getLanguages()->toArray();

        $name = $user->getName();
        echo "<h1>User $name can speak the following languages:</h1><br/>";
        foreach ($languages as $language) {
            echo $language->getLanguageName() . "<br/>";
        }
    }

    public function createAction()
    {
        $userForm = new Form_User();
        if ($this->_request->isPost()) {
            if ($userForm->isValid($_POST)) {
                $data = $userForm->getValues();
                $instance = new \Federico\Entity\UserService($this->em);
                $entity = $instance->makeUser($data);
                $user = $instance->saveUser($entity);
                
                $this->_redirect('federico/index');
            }//else $this->_forward(self) esto es super pseudocode...
            
        }
    //$userForm->setAction('/users/create');
        $this->view->form = $userForm;
    }
    
    public function editAction() 
    {
        $id = $this->getParam(self::PARAM_GET_ID);
        
        $form = new Form_User();
       
        $userModel = $this->em->getRepository('Federico\Entity\User')->getUser($id);

        $languages = $userModel->getLanguages()->toArray();
        $selectedLanguages = array(); //needed countries to populate form
        
        foreach ($languages as $language) {
            $selectedLanguages[] = $language->getLanguageName();
        }

        $form->getElement('id')->setValue($userModel->getId());
        $form->getElement('role')->setValue($userModel->getRole());
        $form->getElement('name')->setValue($userModel->getName());
        $form->getElement('email')->setValue($userModel->getEmail());
        $form->getElement('password')->setValue($userModel->getPassword());
        $form->getElement('url')->setValue($userModel->getUrl());
        $form->getElement('languages')->setValue($selectedLanguages);
        echo $form;
        
        if ($this->_request->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $form->getValues();
                $instance = new \Federico\Entity\UserService($this->em);
                $entity = $instance->makeUser($data);
                $user = $instance->updateUser($entity);

                $this->_redirect('federico/index');
            }
        }
    }
    
    public function deleteAction()
    {
        $id = $this->_request->getParam(self::PARAM_GET_ID);
        
        $user = new \Federico\Entity\UserService($this->em);
        $user->deleteUser($id);
        
        $this->_redirect('federico/index');
    }
    
    private function getParam($param) {
        if(!$value = $this->_request->getParam($param)) {
            throw new Exception("$param was not found");
        }
        return $value;
    }

}

