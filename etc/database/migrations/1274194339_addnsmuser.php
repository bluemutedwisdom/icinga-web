<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addnsmuser extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('nsm_user', array(
             'user_id' => 
             array(
              'type' => 'integer',
              'length' => 4,
              'fixed' => false,
              'unsigned' => false,
              'primary' => true,
              'autoincrement' => true,
             ),
             'user_account' => 
             array(
              'type' => 'integer',
              'length' => 4,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_name' => 
             array(
              'type' => 'string',
              'length' => 18,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_lastname' => 
             array(
              'type' => 'string',
              'length' => 40,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_firstname' => 
             array(
              'type' => 'string',
              'length' => 40,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_password' => 
             array(
              'type' => 'string',
              'length' => 64,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_salt' => 
             array(
              'type' => 'string',
              'length' => 64,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_authsrc' => 
             array(
              'type' => 'string',
              'length' => 45,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_authid' => 
             array(
              'type' => 'string',
              'length' => 127,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => false,
              'autoincrement' => false,
             ),
             'user_authkey' => 
             array(
              'type' => 'string',
              'length' => 64,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => false,
              'autoincrement' => false,
             ),
             'user_email' => 
             array(
              'type' => 'string',
              'length' => 40,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_disabled' => 
             array(
              'type' => 'integer',
              'length' => 1,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'default' => '1',
              'notnull' => true,
              'autoincrement' => false,
             ),
             'user_created' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'user_modified' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             ), array(
             'indexes' => 
             array(
              'user_unique' => 
              array(
              'fields' => 
              array(
               'user_name' => 
               array(
               'sorting' => 'ASC',
               ),
              ),
              'type' => 'unique',
              ),
              'user_search' => 
              array(
              'fields' => 
              array(
               'user_name' => 
               array(
               'sorting' => 'ASC',
               ),
               'user_authsrc' => 
               array(
               'sorting' => 'ASC',
               ),
               'user_authid' => 
               array(
               'sorting' => 'ASC',
               ),
               'user_disabled' => 
               array(
               'sorting' => 'ASC',
               ),
              ),
              ),
             ),
             'primary' => 
             array(
              0 => 'user_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('nsm_user');
    }
}