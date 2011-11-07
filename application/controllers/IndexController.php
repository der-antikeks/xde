<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->identity = $auth->getIdentity();
        } else {
            $this->identity = null;
        }
        
    }

    public function preDispatch()
    {
        if ($this->identity) {
            $this->_forward('index', 'Base');
        }
        
    }
    
    public function indexAction()
    {
        $this->_helper->layout->setLayout('prelogin');
        
    }
    
}
