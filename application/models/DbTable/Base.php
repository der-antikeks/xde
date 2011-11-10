<?php

class Application_Model_DbTable_Base extends Zend_Db_Table_Abstract
{

    protected $_name = 'base';

    protected $_referenceMap    = array(
        'Owner' => array(
            'columns'           => array('owner_id'),
            'refTableClass'     => 'Application_Model_DbTable_User',
            'refColumns'        => array('id')
        )
    );
    
}

