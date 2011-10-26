<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initCache()
	{
		$cache = Zend_Cache::factory('Core', 'File',
			array(	// frontendOptions
				'lifetime'	=> 3600, // 60min
				'automatic_serialization'	=> true,
			),
			array(	// backendOptions
				'cache_dir'	=> '../tmp/',
			));
		
		Zend_Registry::set('Zend_Cache', $cache);
		
		/*
		if (!($cache->test($id))) {
			// nothing cached
			// [...] create data
			// save to cache
			$cache->save($data, $id = null, $tags = array(), $lifetime = null);
		} else {
			// chache hit, load data
			$data = $cache->load($id);
			
			// remove data
			$cache->remove($id);
		}
		*/
		
		return $cache;
	}
	
	protected function _initI18N()
	{
		mb_internal_encoding('UTF-8');
		mb_regex_encoding('UTF-8');
		/*
		$this->bootstrap('cache');
		$cache = $this->getResource('cache');
		Zend_Translate::setCache($cache);
		*/
		$translate = new Zend_Translate(array(
			'adapter'		=> 'array',
			'content'	=> '../languages/en/Zend_Validate.php',
			'locale' 		=> 'en',
		));

		$translate
			->addTranslation(array(
				'content'	=> '../languages/de/Zend_Validate.php',
				'locale' 		=> 'de',
			))
			->addTranslation(array(
				'content'	=> '../languages/en/basic.php',
				'locale' 		=> 'en',
			))
			->addTranslation(array(
				'content'	=> '../languages/de/basic.php',
				'locale' 		=> 'de',
			));

		Zend_Registry::set('Zend_Translate', $translate);
		
		Zend_Form::setDefaultTranslator($translate);
		Zend_Validate_Abstract::setDefaultTranslator($translate);
	}
	
	protected function _initAutoloaders()
	{
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('MH_');
		
		return $loader;
	}
	
	protected function _initLog()
	{
		$this->bootstrap('db'); 
		
		$logger = new Zend_Log();
		
		$writer = new Zend_Log_Writer_Db(Zend_Db_Table::getDefaultAdapter(), 'log');
		$writer->addFilter(new Zend_Log_Filter_Priority(Zend_Log::ERR));
		$logger->addWriter($writer);
		
		Zend_Registry::set('Zend_Log', $logger);
		
		return $logger;
	}
	
}
