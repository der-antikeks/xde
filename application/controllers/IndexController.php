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
			} else {
				$loginForm->buildBootstrapErrorDecorators();
				$this->view->messages = array('<strong>Error!</strong> Please control your input!'); // extra message on top
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
				
				$userTable = new Application_Model_DbTable_User();
				
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
						'email'		=> strtolower($createForm->getValue('email')),
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
        $request = $this->getRequest();
		$lostForm = new Application_Form_UserLost();
		
		$lostForm
			->setAction('/index/lost')
			->setMethod('post');
		
		if ($request->isPost()) {
			if ($lostForm->isValid($request->getPost())) {
				$userTable = new Application_Model_DbTable_User();
				
				$user = $userTable->fetchRow(
					$userTable->select()
						->where('email = ?', strtolower($lostForm->getValue('email')))
				);
				
				if ($user) {
					$hash = md5(uniqid(rand()));
					$user->hash = $hash;
					$user->save();
					
					$url = $request->getScheme() . '://' 
						. $request->getHttpHost() 
						. $this->view->url(array(
							'controller'	=> 'index', 
							'action'		=> 'reset',
							'h'				=> $hash
						), null, true);
					
					$message = 'This is an automated message generated to reset your password. Follow the link below to choose a new one:' . "\n\n"
						. $url . "\n\n"
						. 'Do not reply to this message, which was sent from an unmonitored e-mail address. Mail sent to this address cannot be answered.' . "\n";
					
					$headers = 'From: webmaster@example.com' . "\r\n"
						. 'Reply-To: webmaster@example.com' . "\r\n"
						. 'X-Mailer: PHP/' . phpversion();
					
					if (mail(
						$user->email, 
						'Reset your password',
						$message,
						$headers
					)) {
						$this->view->messages = array('Password reset link has been sent');
					} else {
						$this->view->errors = array('Email could not be sent');
					}
					
				} else {
					$this->view->errors = array('Email address could not be found');
				}
			}
		}
		
		$this->view->form = $lostForm;
        
    }

    public function resetAction()
    {
        $request = $this->getRequest();
		
		if (! $hash = $request->getParam('h')) {
			return $this->view->errors = array('No valid Reset Code found');
		}
		
		$userTable = new Application_Model_DbTable_User();
		$user = $userTable->fetchRow(
			$userTable->select()
				->where('hash = ?', $request->getParam('h'))
		);
		
		if (! $user) {
			return $this->view->errors = array('User could not be found');
		}
			
		$resetForm = new Application_Form_UserPassword();
		
		$resetForm
			->setAction($this->view->url(array(
				'controller'	=> 'index', 
				'action'		=> 'reset',
				'h'				=> $hash
			), null, true))
			->setMethod('post');
		
		if ($request->isPost()) {
			if ($resetForm->isValid($request->getPost())) {
				
				$password = new Zend_Db_Expr('AES_ENCRYPT(CONCAT(' . $userTable->getAdapter()->quote($user->salt) . ', ' . $userTable->getAdapter()->quote($this->salt) . '), ' . $userTable->getAdapter()->quote($resetForm->getValue('password')) . ')');
				$user->password = $password;
				$user->hash = null;
				
				if ($user->save()) {
					$this->view->messages = array('Your Password has been changed');
					return $this->view->changed = true;
				} else {
					$this->view->errors = array('Failed to change password');
				}
				
			}
		}
		
		$this->view->form = $resetForm;
        
    }


}


