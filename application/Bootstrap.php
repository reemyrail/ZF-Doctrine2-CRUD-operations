<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    private $_acl = null;
    private $_auth = null;
    
    public function _initAuth()
    {
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Federico_Plugin_Acl(Zend_Auth::getInstance()));
        
    }
    
    public function _initAutoloader()
    {
        require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';

        $autoloader = \Zend_Loader_Autoloader::getInstance();

        $autoloader->pushAutoloader(new Zend_Loader_Autoloader_Resource(array(
                'basePath' => APPLICATION_PATH,
                'namespace' => '',
                'resourceTypes' => array(
                    'form' => array(
                        'path' => 'forms/',
                        'namespace' => 'Form'
                    )
                  )
                )
              )
        );
        $bisnaAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($bisnaAutoloader, 'loadClass'), 'Bisna');

        $appAutoloader = new \Doctrine\Common\ClassLoader('Federico');
        $autoloader->pushAutoloader(array($appAutoloader, 'loadClass'), 'Federico');
    }
    
    public function _initRoutes()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        
        $route = new Zend_Controller_Router_Route_Static (
            'login',                                               
            array('controller' => 'login', 'action' => 'index')  
        );
        
        $router->addRoute('login', $route);
    }
      
    protected function _initViewDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath('Federico/View/Helper', 'Federico_View_Helper'); 
    }
    
    protected function _initTimeZone()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }
    
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }
    protected function _initEntityManager()
    {
        $this->bootstrap('Doctrine');
        $container = $this->getResource('doctrine');
        $em = $container->getEntityManager();
        Zend_Registry::set('em', $em);
    }
    
    protected function _initNavigation()
	{
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new Federico_Plugin_Acl($this->_auth);
        
		$this->bootstrap('view');

		$view = $this->getResource('view');
		$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml','nav');
		$navigation = new Zend_Navigation($config);
		
        $roleAuth = $this->_auth->getIdentity();
        //Zend_Debug::dump($roleAuth);die;
         if(null == $roleAuth)
            $role = 'guest';
        else
            //var_dump($roleAuth);die;
            $role = $roleAuth->getRole();
        
        $view->navigation($navigation)->setAcl($this->_acl->getAcl())->setRole($role);
    }
    
    protected function _initFlashMessenger()
    {
        /** @var $flashMessenger Zend_Controller_Action_Helper_FlashMessenger */
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        if ($flashMessenger->hasMessages()) {
            $view = $this->getResource('view');
            $view->messages = $flashMessenger->getMessages();
        }
    }

}
