<?php
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    public function loggedInAs() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            if ($auth->hasIdentity() === 'guest') {
                return false;
            } else {
                $username = $auth->getIdentity()->getEmail();
                $logoutUrl = $this->view->url(array('controller' => 'login',
                        'action' => 'logout'), null, true);
                return '<p>Bienvenido ' . '<a href="'. $this->view->url(array(
                    'controller' => 'users', 'action' => 'index'
                )) .'"><span>'. $username .'</span></a></p>'. '. <a id="this-anch" href="' . 
                    $logoutUrl . '"><span>Logout</span></a>';
            }
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'login' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array('controller'=>'login', 'action'=>'index'));
        return '<a href="'.$loginUrl.'"><span>Login</span></a>';
    }
}
