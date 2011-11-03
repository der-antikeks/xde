<?php

class Application_Form_UserCreate extends Zend_Form
{

    public function init()
    {
        $username = $this->createElement('text', 'username')
			->setLabel('Username:')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addValidator(new Zend_Validate_StringLength(array('min' => 3)))
			->addErrorMessage('Please choose a username with at least 3 characters');
			
		$email = $this->createElement('text', 'email')
			->setLabel('Email:')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addFilter(new Zend_Filter_StringToLower())
			->addValidator(new Zend_Validate_EmailAddress());
			
		$password = $this->createElement('password', 'password')
			->setLabel('Password:')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addValidator(new Zend_Validate_StringLength(array('min' => 4)))
			->addErrorMessage('Please choose a password with at least 4 characters');
		
		$password_confirm = $this->createElement('password', 'password_confirm')
			->setLabel('Password (confirm):')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addValidator(new Zend_Validate_Identical(array('token' => 'password')))
			->addErrorMessage('The passwords do not match');
		
		$csrf = $this->createElement('hash', 'csrf')
			->setRequired(true)
			->setIgnore(true)
			->removeDecorator('Label');
		
		$create = $this->createElement('submit', 'create')
			->setLabel('Sign up')
			->setRequired(false)
			->setIgnore(true)
			->setAttribs(array('class' => 'search button'));
		
		$this->addElements(array(
			$username,
			$email,
			$password,
			$password_confirm,
			$csrf,
			$create,
		));
			
		$this->setDecorators(array(
			'FormElements',
			array('HtmlTag', array(
				'tag' 		=> 'dl',
				'class'	=> 'zend_form'
			)),
			array('Description', array(
				'placement'	=> 'prepend'
			)),
			'Form'
		));
		
		$this->setAttrib('accept-charset', 'UTF-8');
		$this->setMethod('post');
		
    }

}
