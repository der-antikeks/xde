<?php

class Application_Form_UserLogin extends Zend_Form
{

    public function init()
    {
		$username = $this->createElement('text', 'username')
			->setLabel('Email:')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addFilter(new Zend_Filter_StringToLower())
			->addValidator(new Zend_Validate_EmailAddress());
		
		$password = $this->createElement('password', 'password')
			->setLabel('Password:')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addValidator(new Zend_Validate_StringLength(array('min' => 3)));
		
		$csrf = $this->createElement('hash', 'csrf')
			->setRequired(true)
			->setIgnore(true)
			->removeDecorator('Label');
		
		$login = $this->createElement('submit', 'login')
			->setLabel('Login')
			->setRequired(false)
			->setIgnore(true)
			->setAttribs(array('class' => 'search button'));
		
		$this->addElements(array(
			$username,
			$password,
			$csrf,
			$login,
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
