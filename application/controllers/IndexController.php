<?php

class IndexController extends Zend_Controller_Action
{

	public function init()
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$this->identity = $auth->getIdentity();
		}
		
	}

	public function indexAction()
	{
		// action body
	}

	public function loginAction()
	{
		// action body
	}
	
}
