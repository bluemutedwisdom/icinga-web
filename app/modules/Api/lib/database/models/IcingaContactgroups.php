<?php

/**
 * IcingaContactgroups
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class IcingaContactgroups extends BaseIcingaContactgroups
{
	public function __get($field) {
		switch($field) {
			case 'hosts':
				$hosts = $this->getHosts();
				$this->set("hosts",$hosts);
				return $hosts;
				break;	
		}
		return parent::__get($field);
	}

	public function getHosts() {
		return Doctrine_Query::create()
				->select("h.*")
				->from("IcingaHosts h")
				->innerJoin("h.contactgroups cg ON cg.contactgroup_object_id = "
					.$this->contactgroup_object_id." AND cg.instance_id = ".$this->instance_id.
					" AND cg.host_id = h.host_id")
				->execute(null,Doctrine_Core::HYDRATE_RECORD);
	}

}
