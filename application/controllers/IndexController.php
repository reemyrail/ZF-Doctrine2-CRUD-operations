<?php

class IndexController extends Zend_Controller_Action
{
    
    public function init() {
        $uri = $this->_request->getPathInfo();
        $activeNav = $this->view->navigation()->findByUri($uri);
        $activeNav->active = true;

        $this->em = Zend_Registry::get('em');
    }

    public function indexAction()
    {

    }
}





