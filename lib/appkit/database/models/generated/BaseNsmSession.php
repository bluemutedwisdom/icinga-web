<?php

/**
 * BaseNsmSession
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $session_entry_id
 * @property string $session_id
 * @property string $session_name
 * @property blob $session_data
 * @property string $session_checksum
 * @property timestamp $session_created
 * @property timestamp $session_modified
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
abstract class BaseNsmSession extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('nsm_session');
        $this->hasColumn('session_entry_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 0,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('session_id', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('session_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('session_data', 'blob', null, array(
             'type' => 'blob',
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('session_checksum', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('session_created', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('session_modified', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

}