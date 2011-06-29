<?php

class FedericoController extends Zend_Controller_Action
{

    public function init() {
        $this->em = Zend_Registry::get('em');
        $this->conn = $this->em->getConnection();
    }

    public function indexAction()
    {
		$usr = $this->em->getRepository('Federico\Entity\User')->getUsersLanguages();
        $this->view->show = $usr;
    }
}

