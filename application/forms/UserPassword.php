<?php

class Application_Form_UserPassword extends Zend_Form
{

    public function init()
    {
		$this->setAttrib('accept-charset', 'UTF-8');
		$this->setMethod('post');
		$this->setAction('/index/reset');
		
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
		
		$send = $this->createElement('submit', 'send')
			->setLabel('Send')
			->setRequired(false)
			->setIgnore(true)
			->setAttribs(array('class' => 'search button'));
			
		$this->addElements(array(
			$password,
			$password_confirm,
			$csrf,
			$send,
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
		
    }


}

