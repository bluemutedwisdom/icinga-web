<?php
/**
 * Abstract base class for all tasks accessing the icinga-web doctrine implementation
 * 
 */

class doctrineTask extends Task {
	protected $icingaPath = "/usr/local/icinga-web/";
	protected $modelPath = "app/modules/AppKit/lib/database/models/";
	protected $dsn;
	protected $action;
	
	public function setAction($action) {
		$this->action = $action;
	}
	
	public function setIcingapath($path) {
		$this->icingaPath = $path;
	}
	public function setModelpath($path) {
		$this->modelPath = $path;
	}
	public function setDsn($conn) {
		$this->dsn = $conn;
	}
	
	public function init() {
		// include doctrine
		require_once($this->icingaPath."lib/doctrine/lib/Doctrine.php");
		spl_autoload_register("Doctrine::autoload");
	}
	
	public function main() {
		Doctrine_Manager::connection($this->dsn,"mainConnection");
	
		Doctrine::loadModels($this->modelPath."generated/");
		Doctrine::setModelsDirectory($this->modelPath."/");
		if($this->action == 'dropDB')
			$this->dropDB();
	}
	
	public function dropDB() {
		Doctrine::dropDatabases("mainConnection");
	}
}