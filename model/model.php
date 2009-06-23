<?php
if(!$iWasThere)
     {
          die("go away loser");
     }

Load()->lib('Exception');

class Model {

     protected $connection;
     protected $result;

	protected $stmt;
	protected $varsBound = false;
	protected $results;

	protected $table;
	protected $fields;
	protected $select;
	protected $from;
	protected $where = "";
	protected $limit;

     public $lastInsertID;

     private function __construct($connection) {
		$this->connection = $connection;
     }

	function query($q, $binds = array()) {

		$stmt = $this->connection->prepare($q);

		if (is_array($binds) && count($binds)) {

			array_unshift($binds, $this->getPreparedTypeString($binds));
			// call $stmt->bind_param('', $binds);
               call_user_func_array(array(&$stmt, 'bind_param'), $binds);
		}

          if ($stmt) {
          	$stmt->execute();
          } else {
          	throw new Seven12_Exception("Error Executing Some Query: " . $this->connection->error);
          }

          if ($stmt->errno) {
          	throw new Seven12_Exception("Error executing Query: " . $stmt->error);
          }

          if ($this->connection->insert_id) {
          	$this->lastInsertID = $this->connection->insert_id;
          }

          $stmt->store_result();

		$this->stmt = $stmt;

		return true;

	}

	public static function getPreparedTypeString(&$saParams) {
            $sRetval = '';

            //if not an array, or empty.. return empty string
            if (!is_array($saParams) || !count($saParams))
            {
                return $sRetval;
            }

            //iterate the elements and figure out what they are, and append to result
            foreach ($saParams as $Param) {

                if (is_int($Param)) {
                    $sRetval .= 'i';
                } else if (is_double($Param)) {
                    $sRetval .= 'd';
                } else if (is_string($Param)) {
                    $sRetval .= 's';
                } // there is also `b` for blob
            }

            return $sRetval;
     }

	public function fetch_assoc() {
        // checks to see if the variables have been bound, this is so that when
        //  using a while ($row = $this->stmt->fetch_assoc()) loop the following
        // code is only executed the first time
        if (!$this->varsBound) {
            $meta = $this->stmt->result_metadata();
            while ($column = $meta->fetch_field()) {
                // this is to stop a syntax error if a column name has a space in
                // e.g. "This Column". 'Typer85 at gmail dot com' pointed this out
                //$columnName = str_replace(' ', '_', $column->name);

                $bindVarArray[] = &$this->results[$column->name];
            }
            call_user_func_array(array($this->stmt, 'bind_result'), $bindVarArray);
            $this->varsBound = true;
        }

        if ($this->stmt->fetch() != null) {
            // this is a hack. The problem is that the array $this->results is full
            // of references not actual data, therefore when doing the following:
            // while ($row = $this->stmt->fetch_assoc()) {
            // $results[] = $row;
            // }
            // $results[0], $results[1], etc, were all references and pointed to
            // the last dataset
  //          dump($this->results);
            foreach ($this->results as $k => $v) {
                $results[$k] = $v;
            }
            return $results;
        } else {
            return null;
        }
	}

	// if you want to return an object you should create
	// class and assign $row to it in the upper function
	function fetch() {
		$result = array();
		while ($row = $this->fetch_assoc()) {
			// r[] = new Object($row);
		    	$result[] = $row;
		}
		$this->stmt->free_result();
		$this->stmt->close();
		return $result;
	}


	function getResult() {
		return $this->result;
	}

	function select($fields = "*") {
    		$this->select =  "SELECT " . (is_array($fields) ? "`" . implode('`, `', $fields) . "`" : $fields);
    		return $this;
	}

	function from($table) {
		$this->from = "FROM " . $table;
		return $this;
	}

	function where($where) {
		if (is_array($where)) {
    			$this->where = "WHERE " . $this->getWhere($where);
	    	}
	    	return $this;
	}

	public function limit($limit = 20, $offset = null) {
            $this->limit = "LIMIT " . (int) $limit;
            if(null !== $offset) {
                    $this->limit .= " OFFSET " . (int) $offset;
            }
            return $this;
     }

	public function delete(array $conditions) {
		$sql = "DELETE FROM " . $this->table . " ";
		if (is_array($conditions)) {
    			$sql .= "WHERE " . $this->getWhere($conditions);
	    	}

		$result = $this->query($sql, $this->getParameters());
		return true;
	}

	public function insert(array $what)
	{
		$fields = array_keys($what);
		$this->params = array_values($what);
		$q = array_fill(0, count($what), '?');

		if(count($what) > 0) {
			// build the statement
			$sql = "INSERT INTO " . $this->getTable() .
				" (" . implode(', ', $fields) . ")" .
				" VALUES(" . implode(', ', $q) . ")";

			if ($this->query($sql, $this->getParameters())) {
				$result = true;
			} else {
				$result = true;
			}

		} else {
			$result = false;
		}

		return $result;
	}

	function update(array $row, $id) {

		$keys = array_keys($row);
		$this->params = array_values($row);
		$this->params['id'] = $id;

		foreach($keys as $key) {
			$placeholders[] = $key . " = ?";
		}

		if(count($placeholders) > 0) {
				// Build the query
			$sql = "UPDATE " . $this->getTable() .
				" SET " . implode(', ', $placeholders) .
				" WHERE `id` = ?";

			if ($this->query($sql, $this->getParameters())) {
				$result = true;
			} else {
				$result = false;
			}

		} else {
			$result = false;
		}

		return $result;
	}

		// build join      join()->on()->where();
	function join($table, array $where, $type) {

    		if (is_array($where)) {
    			$where = $this->getSimpleWhere($where);
    		}

    		$sql = "SELECT `" . $this->getTable() . "`.*, `" . $table . "`.* " .
    			"FROM `" . $this->getTable() . "`" .
    			$type . " join `" . $table ."` on " . $where;

    		if ($this->query($sql, $this->getParameters())) {
			$result = true;
		} else {
			$result = false;
		}
    	}

	function setTable($table) {
		$this->table = $table;
	}

	function getTable() {
		return $this->table;
	}

	function getParameters() {
		return $this->params;
	}

     function getWhere($where) {
    		foreach($where as $k=>$v) {
    			$params[]	= $v;
    			$w[] 	= "`$k` = ?";
    		}
    		$this->params = $params;
    		$where = implode(' AND ', $w);

    		return $where;
     }

	function getSimpleWhere($where) {

		foreach ($where as $k => $v) {

			$w[] = "$k = $v";

		}
		return implode(' AND ', $w);
     }

	/**
     * Get raw SQL code generated from other query builder functions
     */
     public function sql() {
             $sql = $this->select . " \n"
                     . $this->from . " \n"
                     . $this->where;
             return $sql;
     }


     /**
      * Return Sql code with $this->sql() function
      */
     public function __toString() {
             return $this->sql();
     }

}
?>
