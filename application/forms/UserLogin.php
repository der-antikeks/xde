<?php

class Application_Form_UserLogin extends EasyBib_Form // Zend_Form
{

    public function init()
    {
		// configure form
		$this->setAttrib('accept-charset', 'UTF-8');
		$this->setMethod('post');
		$this->setAction('/index/login');
		
		// create input elements
		$username = $this->createElement('text', 'username')
			->setLabel('Email address')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addFilter(new Zend_Filter_StringToLower())
			->addValidator(new Zend_Validate_EmailAddress())
			//->setDescription('email address')
			->setAttribs(array('class' => 'xlarge'));
		
		$password = $this->createElement('password', 'password')
			->setLabel('Password')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addValidator(new Zend_Validate_StringLength(array('min' => 3)))
			->setAttribs(array('class' => 'xlarge'));
		
		$csrf = $this->createElement('hash', 'csrf')
			->setRequired(true)
			->setIgnore(true)
			->removeDecorator('Label');
		
		// 
		$link = new MH_Form_Element_Html('link');
		$link->setRequired(false)
			->setAttribs(array(
				'type' 		=> 'a', 
				'href'			=> '/index/lost', 
				'content'	=> 'Forgot your password?'
			));
		
		// create button elements
		$login = $this->createElement('submit', 'login')
			->setLabel('Sign In')
			->setRequired(false)
			->setIgnore(true)
		//	->setAttribs(array('class' => 'search button'))
			->removeDecorator('Label');
		
		$cancel = $this->createElement('reset', 'cancel')
			->setLabel('Cancel')
			->setRequired(false)
			->setIgnore(true)
			->removeDecorator('Label');
		
		// add elements
		$this->addElements(array(
			$username,
			$password,
			$csrf,
			$link,
			$login,
			$cancel,
		));
		
		// add display group
		/*
		$this->addDisplayGroup(
			array('username', 'password', 'csrf', 'login', 'cancel'),
			'users'
		);
		$this->getDisplayGroup('users')->setLegend('Add User');
		*/
		// set decorators
		EasyBib_Form_Decorator::setFormDecorator(
			$this, 
			EasyBib_Form_Decorator::BOOTSTRAP, 
			'login', 
			'cancel'
		);
		
    }

}
