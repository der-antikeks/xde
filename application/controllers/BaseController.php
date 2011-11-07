<?php

class BaseController extends Zend_Controller_Action
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
        $userTable = new Application_Model_DbTable_User();
        
        $user = $userTable->find($this->identity->id)->current();
        if (! $user) throw Exception('logged in, but could not find user');
        
        $base = $user->findDependentRowset('Application_Model_DbTable_Base', 'Owner')->current();
        
        if (! $base) {
            // first time, create base
            $baseTable = new Application_Model_DbTable_Base();
            $base = $baseTable->createRow(array(
                'owner_id'  => $user->id
            ));
            
            if (! $base->save()) throw Exception('failed to create initial base');
        }
        
        Zend_Debug::dump($base->toArray());
    }


}

