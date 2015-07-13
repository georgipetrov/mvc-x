<?php
class db {
	private static $instance = NULL;
	public static $log = array();

	public static function getInstance($db) {
        if (empty($db['type']) || empty($db['host']) || empty($db['username']) || empty($db['password'])) {
            throw new Exception("Incomplete database details");
        }

		if (!self::$instance) {
            $dest_string = "$db[type]:host=$db[host]";
            if (!empty($db['name'])) {
                $dest_string .= ";dbname=$db[name]";
            }
			self::$instance = new PDO($dest_string, $db['username'], $db['password']);;
			self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$instance;
	}

    public static function renewInstance($db) {
        if (self::$instance) {
            self::$instance = null;
        }

        return self::getInstance($db);
    }
	
	public static function query($query) {
		
		if (self::$instance == NULL) {
			return false;	
		}

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

	private function __clone() {
		
	}

}
