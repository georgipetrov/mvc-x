<?php

abstract class Model extends Base {
	
	/*
	 * @registry object
	 */
	protected $dbname;
	protected $table;
	protected $tableprefix;
	protected $tablecolumns = array();
	protected $paginate = false;
	
	function __construct($registry) {
        parent::__construct($registry);
		$this->tableprefix = !empty($this->app->dbconfig['table_prefix']) ? $this->app->dbconfig['table_prefix'] : '';
		$this->dbname = !empty($this->app->dbconfig['name']) ? $this->app->dbconfig['name'] : '';
        if (empty($this->table)) {
            $table_name = strtolower(get_class($this));
            $this->table = $table_name;
        }
	}
	
	function query($query) {
		$stmt = db::query($query);
		if (stripos($query,'select ') !== false || stripos($query,'show ') !== false) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
		$paginate = false;
		if (strpos($easyquery,'paginate') === 0) { // Found
			$paginate = true;
			$easyquery = str_replace('paginate','get',$easyquery);
		}
		if (strpos($easyquery,'select') === 0) $easyquery = str_replace('select','get',$easyquery);
		if (strpos($easyquery,'find') === 0) $easyquery = str_replace('find','get',$easyquery);
		if (strpos($easyquery,'get') === 0) { // Found
			$db = $this->dbname;
			$table = $this->tableprefix.$this->table;
			$fields = '*';
			$limit = '';
			$order = '';
			$group = '';
			$query = '';
			$easyquery = str_replace('get','',$easyquery);

			$parts = explode('By',$easyquery);
			if (isset($parts[0])) { 
				// There is no 'By'
				$fields = $this->eqToSql('fields',$parts[0]);
				$query = "SELECT $fields FROM `$db`.`$table` ";
			}
			
			if (empty($parts[1]) && strpos($parts[0],'by')) {
				$direction = "";
				$sortparts = explode('Sortby',$parts[0]);
				$parts[0] = $sortparts[0];

				if (strpos($sortparts[1],'Asc') !== false) {
					$direction = " ASC";
					$sortparts[1] = str_replace("Asc","",$sortparts[1]);
				}
				if (strpos($sortparts[1],'Desc') !== false) {
					$direction = " DESC";
					$sortparts[1] = str_replace("Desc","",$sortparts[1]);
				}	
				if (!empty($sortparts[1])) {
					$cwordparts = preg_split('/(?=[A-Z])/', $sortparts[1], -1, PREG_SPLIT_NO_EMPTY);
					$column = strtolower(implode('_',$cwordparts));
					$order = " ORDER BY `".$column."`".$direction;
				}
			}
			
			if (isset($parts[1]) && strpos($parts[1],'Orderby') !== false) {
				$easyquery = str_replace('Orderby','Sortby',$easyquery);
			}

			if (isset($parts[1]) && strpos($parts[1],'Groupby') !== false) { 
			
				$direction = "";
				$sortparts = explode('Groupby',$parts[1]);
				$parts[1] = $sortparts[0];
				
				if (!empty($sortparts[1])) {
					$cwordparts = preg_split('/(?=[A-Z])/', $sortparts[1], -1, PREG_SPLIT_NO_EMPTY);
					$column = strtolower(implode('_',$cwordparts));
					$group = " GROUP BY `".$column."`";
				}

			}

	
			if (isset($parts[1]) && strpos($parts[1],'Sortby') !== false) {
				$direction = "";
				$sortparts = explode('Sortby',$parts[1]);
				$parts[1] = $sortparts[0];

				if (strpos($sortparts[1],'Asc') !== false) {
					$direction = " ASC";
					$sortparts[1] = str_replace("Asc","",$sortparts[1]);
				}
				if (strpos($sortparts[1],'Desc') !== false) {
					$direction = " DESC";
					$sortparts[1] = str_replace("Desc","",$sortparts[1]);
				}	
				if (!empty($sortparts[1])) {
					$cwordparts = preg_split('/(?=[A-Z])/', $sortparts[1], -1, PREG_SPLIT_NO_EMPTY);
					$column = strtolower(implode('_',$cwordparts));
					$order = " ORDER BY `".$column."`".$direction;
				}
				
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
			
			$query .= $group;
			$query .= $order;
			
			if ($paginate) {
				$page_num = returnine($this->request->data['pagenumber'],1);
				$results_per_page = returnine($this->paginate['results_per_page'],10);
				$start = ($page_num-1)*$results_per_page;
				$limit = " LIMIT $start, $results_per_page";
				$this->paginate=false;
				if (empty($group)) {
					$query_count = str_replace(' * ', ' COUNT(*) ',$query);
					$query_result = current($this->query($query_count));
					$max_count = (int)@array_pop($query_result);
				} else {
					$max_count =count($this->query($query));	
				}
				$this->request->data['_paginate_current_page_number_'] = $page_num;
				$this->request->data['_paginate_results_per_page_'] = $results_per_page;
				$this->request->data['_paginate_total_page_number_'] = ceil($max_count/$results_per_page);				
				$this->request->data['_paginate_all_results_count_'] = $max_count;	
		
			}
            
            $query .= $limit;
			
			$query_result = $this->query($query);
			$fullcolumns = $this->query("SHOW FULL COLUMNS FROM $table");
			foreach ($fullcolumns as $col) {
				if (!empty($col['Comment']) && !empty($col['Field']) && strpos($col['Comment'], '.') != false) {
					$comment_exploded = explode('.',$col['Comment']);
					$comment_table = $comment_exploded[0];
					$comment_field = returnine($comment_exploded[1]);
					if (empty($comment_field)) continue;
					
					foreach ($query_result as $k => $qr) {
						$qr_field_value = $qr[$col['Field']];
						$qr_query_result = $this->query("SELECT * FROM `".$this->tableprefix.$comment_table."` WHERE `".$comment_field."` = '".$qr_field_value."'");
						$query_result[$k][$comment_table] = current($qr_query_result);
					
					}
				}
			}
			return $query_result;

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
			$entry['ip'] = $this->lib->getIp();
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
			if (isset($columns['modified']) && !isset($entry['modified'])) {
				$entry['modified'] =date("Y-m-d H:i:s");
			}
			$values = implode(', ', array_fill(0, count($entry), '?'));
			$keys = "`".implode("`,`",array_keys($entry))."`";
			$query = "INSERT INTO $db.$table ($keys) VALUES ($values)";
			//return $this->query($query);
            $stmt = $this->app->dbinstance->prepare($query);
            return $stmt->execute(array_values($entry));
		} else {
			if (isset($columns['modified']) && !isset($entry['modified'])) {
				$entry['modified'] =date("Y-m-d H:i:s");
			}
			$id = $entry['id'];
			unset($entry['id']);
			$keyvalues = '';
			foreach ($entry as $k => $vals) {
				$keyvalues .= "`$k`=?,";//'$vals',";
			}
			$keyvalues = rtrim($keyvalues,',');

			$query = "UPDATE `$db`.`$table` SET $keyvalues WHERE `id`='$id'";
            $stmt = $this->app->dbinstance->prepare($query);
            return $stmt->execute(array_values($entry));
		}
	}
	
	public function delete($id='') {
		return $this->deleteEntry($id);	
	}
	
	function deleteEntry($id='') {
		$db = $this->dbname;
		$table = $this->tableprefix.$this->table;

		if (!empty($id)) {
			$this->query("DELETE FROM `$db`.`$table` WHERE `id` = $id");
			return true;
		}
	}
	
	function lastId() {
		return $this->app->dbinstance->lastInsertId();	
	}

}
