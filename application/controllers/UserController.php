<?php

class UserController extends Zend_Controller_Action
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
        }
        
    }

    public function indexAction()
    {
        // action body
    }

    public function emailAction()
    {
        // action body
    }

    public function passwordAction()
    {
        // action body
    }
	
    public function preferencesAction()
    {
        // action body
    }
	
    public function deleteAction()
    {
        // action body
    }
	
}




