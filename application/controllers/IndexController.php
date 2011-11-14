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
        
		$this->salt = 'ƒ«Ps¶uƒÒû.Ëv6Ô½Éâ8j‰–©æøþSÐ';
		$this->_helper->layout->setLayout('prelogin');
		
    }

    public function preDispatch()
    {
        if ($this->identity) {
         //   $this->_forward('index', 'Base');
        }
        
    }
    
    public function indexAction()
    {
        $this->_helper->layout->setLayout('simple');
        
    }
    
	
    public function loginAction()
    {
        $request = $this->getRequest();
		$loginForm = new Application_Form_UserLogin();
		
		$loginForm
			->setAction('/index/login')
			->setMethod('post');
		
		if ($request->isPost()) {
			if ($loginForm->isValid($request->getPost())) {
				$db = Zend_Db_Table::getDefaultAdapter();
                $adapter = new Zend_Auth_Adapter_DbTable(
                    $db,
                    'user',
                    'email',
                    'password',
					'AES_ENCRYPT(CONCAT(`salt`, ' . $db->quote($this->salt) . '), ?)'
                );
				
                $adapter
                    ->setIdentity($loginForm->getValue('username'))
                    ->setCredential($loginForm->getValue('password'));
				
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($adapter);
				
                if ($result->isValid()) {
					$identity = $adapter->getResultRowObject(
						array('id', 'role', 'language', 'name')
						//array('password', 'salt')
					);
					
					$auth->getStorage()->write($identity);
					
                    $this->_helper->FlashMessenger('Successful Login');
                    return $this->_forward('index', 'Index');
                } else {
					$auth->clearIdentity();
                    $this->view->messages = $result->getMessages();
                }
			}
		}
		
		$this->view->form = $loginForm;
        
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        
		$this->_helper->FlashMessenger('Successful Logout');
        //$this->view->messages = (is_array($this->view->messages) ? $this->view->messages : array()) + array('Successful Logout');
		$this->_forward('index', 'Index');
        
    }

    public function createAction()
    {
        $request = $this->getRequest();
		$createForm = new Application_Form_UserCreate();
		
		$createForm
			->setAction('/index/create')
			->setMethod('post');
		
		if ($request->isPost()) {
			if ($createForm->isValid($request->getPost())) {
				
				$userTable = new Zend_Db_Table(array(
					'name'	=> 'user',
					'primary'	=> 'id'
				));
				
				if ($userTable->fetchRow(
					$userTable->select()
						->where('name = ?', $createForm->getValue('username'))
				)) {
					$createForm->getElement('username')->setErrors(array('Username is already taken'));
				} else if ($userTable->fetchRow(
					$userTable->select()
						->where('email = ?', $createForm->getValue('email'))
				)) {
					$createForm->getElement('email')->setErrors(array('Email is already registered'));
				} else {
					$user_salt = md5(uniqid(rand()), true);
					
					$locale  = new Zend_Locale();
					
					$identity = new stdClass();
					$identity->role = 'user';
					$identity->language = $locale->__toString();
					$identity->name = $createForm->getValue('username');
					
					$id = $userTable->insert(array(
						'role'			=> $identity->role,
						'email'		=> $createForm->getValue('email'),
						'language'	=> $identity->language,
						'name'		=> $identity->name,
						'password'	=> new Zend_Db_Expr('AES_ENCRYPT(CONCAT(' . $userTable->getAdapter()->quote($user_salt) . ', ' . $userTable->getAdapter()->quote($this->salt) . '), ' . $userTable->getAdapter()->quote($createForm->getValue('password')) . ')'),
						'salt'	        => $user_salt,
					));
					
					$identity->id = $id;
					
					$auth = Zend_Auth::getInstance();
					$auth->getStorage()->write($identity);
					
					$this->_helper->FlashMessenger('Successful Login');
					return $this->_forward('index', 'Index');
				}
				
			}
		}
		
		$this->view->form = $createForm;
        
    }

    public function lostAction()
    {
        // action body
    }

}
