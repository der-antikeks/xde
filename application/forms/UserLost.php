<?php

class Application_Form_UserLost extends Zend_Form
{

    public function init()
    {
		$this->setAttrib('accept-charset', 'UTF-8');
		$this->setMethod('post');
		$this->setAction('/index/lost');
		
		$email = $this->createElement('text', 'email')
			->setLabel('Email:')
			->setRequired(true)
			->addFilter(new Zend_Filter_StringTrim())
			->addFilter(new Zend_Filter_StringToLower())
			->addValidator(new Zend_Validate_EmailAddress());
		
		$this->addElement('captcha', 'captcha', array(
			'label'			=> 'Please verify you\'re human',
			'required'	=> true,
			'captcha'	=> array(
				'captcha'	=> 'Figlet', // ReCaptcha
				'wordLen'	=> 3, 
				'timeout'		=> 300,
				/*
				'privKey'	=> ''.
				'pubKey'	=> '',
				*/
			),
		));

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
			$email,
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

