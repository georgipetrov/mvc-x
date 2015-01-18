<?php
class db {

	/*** Declare instance ***/
	private static $instance = NULL;
	
	public static $log = array();
	
	/**
	*
	* the constructor is set to private so
	* so nobody can create a new instance using new
	*
	*/
	private function __construct() {
	  /*** maybe set the db name here later ***/
	}
	
	/**
	*
	* Return DB instance or create intitial connection
	*
	* @return object (PDO)
	*
	* @access public
	*
	*/
	public static function getInstance($db) {
		if (!self::$instance) {
			self::$instance = new PDO("$db[type]:host=$db[host];dbname=$db[name]", $db['username'], $db['password']);;
			self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$instance;
	}
	
	public static function query($query) {
		
		$start = microtime(true);
		$stmt = self::$instance->query($query);
		
		$time = microtime(true) - $start;
		self::$log[] = array('query'=>$query,'time'=>round($time,5));
		return $stmt;
	}
	
	public static function table_exists($table_name) {
		try {
			self::query('select 1 from `'.$table_name.'`;');
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	*
	* Like the constructor, we make __clone private
	* so nobody can clone the instance
	*
	*/
	private function __clone() {
		
	}

}