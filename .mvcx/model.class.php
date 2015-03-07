<?php

abstract class Model extends Base {
	
	/*
	 * @registry object
	 */
	protected $dbname;
	protected $table;
	protected $tableprefix;
	protected $tablecolumns = array();
	
	function __construct($registry) {
        parent::__construct($registry);
		$this->tableprefix = $this->app->dbconfig['table_prefix'];
		$this->dbname = $this->app->dbconfig['name'];
		$this->table = $this->app->router->controller;
	}
	
	function query($query) {
		$stmt = db::query($query);
		if (stripos($query,'select ') !== false || stripos($query,'show ') !== false) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) == 1) {
				return array_pop($result);
			}
			return $result;
		}
		return $stmt;
	}
	
	public function __call($method, $args) {
       if(property_exists($this, $method)) {
           if(is_callable($this->$method)) {
               return call_user_func_array($this->$method, $args);
           }
       } else {
		   return $this->easyQuery($method,$args);
	   }
   	}
	
	function eqToSql($operand,$eq) {
		switch($operand) {
			case 'fields':
				$fields = '*';
				if (strpos($eq, 'And') !== false) {
					$ands = explode('And',$eq);
					$fields = '';
					foreach ($ands as $and) {
						$fields .= '`'.strtolower($and).'`'.',';
					}
					$fields = rtrim($fields,',');
				}
				return $fields;
			break;

		}
	}
	
	function easyQuery($easyquery, $args) {
		
		if (strpos($easyquery,'get') !== false) {
			$db = $this->dbname;
			$table = $this->tableprefix.$this->table;
			$fields = '*';
			$limit = '';
			$order = '';
			$query = '';
			$easyquery = str_replace('get','',$easyquery);
			
			$parts = explode('By',$easyquery);
			if (isset($parts[0])) { 
				// There is no 'By'
				$fields = $this->eqToSql('fields',$parts[0]);
				$query = "SELECT $fields FROM `$db`.`$table` ";
			}
			
			if (isset($parts[1])) { 
				$ands =  preg_split( "/(And|Or)/", $parts[1] );
				$where = 'WHERE ';
				foreach ($ands as $k => $and) {
					$cwordparts = preg_split('/(?=[A-Z])/', $and, -1, PREG_SPLIT_NO_EMPTY);
					$column = strtolower(implode('_',$cwordparts));
					
					if (!isset($args[$k])) {
						throw new Exception('Missing argument for column: '.$column);
					}
					
					$value = $args[$k]; 
					
					$operator = (strpos($parts[1],$and.'And') !== false) ? 'AND ' : 'OR ';
					$andword = (isset($ands[$k+1])) ? $operator : '';
					$sign = (strpos($value,'%') !== false) ? 'LIKE' : '=';
					$sign = (strpos($value,'>') !== false) ? '>' : $sign;
					$sign = (strpos($value,'<') !== false) ? '<' : $sign;
					$sign = (strpos($value,'<>') !== false) ? '<>' : $sign;
					$sign = (strpos($value,'!=') !== false) ? '!=' : $sign;
					$value = str_replace(array('>','<','<>','!='),'',$value);
					$q = (is_numeric($value)) ? '' : '"';
					$where .= "`$table`.`$column` $sign $q$value$q $andword";
				}
				$query .= $where;
			}
			
			return $this->query($query);

		}
	}
	
	function getColumns(){
		$db = $this->dbname;
		$table = $this->tableprefix.$this->table;
		if (!empty($this->tablecolumns[$table])) {
			return $this->tablecolumns[$table];
		}
		$result = $this->query("SHOW COLUMNS FROM $db.$table");
		$columns = array();
		foreach ($result as $r) {
			$columns[$r['Field']] = $r['Type'];
		}
		$this->tablecolumns[$table]= $columns;
		return $columns;
	}
	
	function save($data) {
		$db = $this->dbname;
		$table = $this->tableprefix.$this->table;
		if (isset($data[0])) {
			$return = false;
			foreach ($data as $entry) {
				$return = $this->saveEntry($entry);
			}
			return $return;
		} else {
			return $this->saveEntry($data);
		}
		return false;
	}
	
	function saveEntry($entry) {
		$db = $this->dbname;
		$table = $this->tableprefix.$this->table;
		$columns = $this->getColumns();

		if (isset($columns['ua'])&& !isset($entry['ua']) && isset($_SERVER['HTTP_USER_AGENT'])) {
			$entry['ua'] = $_SERVER['HTTP_USER_AGENT'];
		}
		if (isset($columns['ip']) && !isset($entry['ip'])) {
			$entry['ip'] = $this->app->lib->getIp();
		}
		
		foreach($entry as $k=> $e) {
			if(!array_key_exists($k,$columns)) {
				unset($entry[$k]);
			}
		}
		
		$doinsert = true;
		if (isset($entry['id'])) {
			$existing = $this->query("SELECT * FROM `$db`.`$table` WHERE `id` = $entry[id] LIMIT 1");
			if (!empty($existing)) {
				$doinsert = false;
			}
		}
		if ($doinsert == true) {
			if (isset($columns['created']) && !isset($entry['created'])) {
				$entry['created'] =date("Y-m-d H:i:s");
			}
			$values = "'".implode("','",$entry)."'";
			$keys = "`".implode("`,`",array_keys($entry))."`";
			$query = "INSERT INTO $db.$table ($keys) VALUES ($values)";
			return $this->query($query);
		} else {
			if (isset($columns['modified']) && !isset($entry['modified'])) {
				$entry['modified'] =date("Y-m-d H:i:s");
			}
			$id = $entry['id'];
			unset($entry['id']);
			$keyvalues = '';
			foreach ($entry as $k => $vals) {
				$keyvalues .= "`$k`='$vals',";	
			}
			$keyvalues = rtrim($keyvalues,',');

			$query = "UPDATE `$db`.`$table` SET $keyvalues WHERE `id`='$id'";
			return $this->query($query);
		}
		
	}
	
	function lastId() {
		return $this->app->dbinstance->lastInsertId();	
	}

}
