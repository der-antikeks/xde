<?php

class TestController extends Zend_Controller_Action
{

    public function init()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->identity = $auth->getIdentity();
        }
        
    }
    
    public function preDispatch()
    {
        if (! $this->identity) {
            $this->_forward('unknownuser', 'Error');
        }
        
    }
    
    public function indexAction()
    {
        Zend_Debug::dump($this->identity, 'identity: ');
        
    }

    public function adminAction()
    {
        if (! ($this->identity->role == 'admin')) {
            $this->_forward('noadmin', 'Error');
        }
        
    }

}
