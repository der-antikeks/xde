<?php

class AdminController extends Zend_Controller_Action
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
        if (! $this->identity) {
            $this->_forward('unknownuser', 'Error');
        } elseif (! ($this->identity->role == 'admin')) {
            $this->_forward('noadmin', 'Error');
        }
        
    }
    
    public function indexAction()
    {
        // action body
    }


}

