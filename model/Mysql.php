<?php
if(!$iWasThere)
     {
          die("go away loser");
     }

class MySQL {

	private static $connection = null;
	protected $host		= 'localhost';
	protected $database;
	protected $username;
	protected $password;
	protected $char_set		= 'latin1';//'utf8';//latin1
	protected $dbcollat		= 'latin1_swedish_ci';//'utf8_general_ci';//latin1_swedish_ci


     private $result 	= null;

     private function __construct() { }

	public function getInstance() {
		try {
		 	if (null === self::$connection) {
		 		self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		 		
				if (self::$connection->connect_errno) {
					throw new Seven12_Exception(__METHOD__ . " Error connecting to mysql server ( " .
							 		$conn->connect_errno . " )<pre>" . 
							 		$conn->connect_error . "</pre>");
				}
			}			

			return self::$connection;
			
		} catch (Exception $e) {
			echo $e->getError();
			exit();
		}
	}

	public function getConnection() {
		return self::$connection;
	}
	
	/**
	 * Set client character set
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	resource
	 */
	public function setCharset($charset, $collation) {
		return mysqli_query(self::$connection, "SET NAMES '".$charset."' COLLATE '".$collation."'");
	}

    function closeConnection() {
	  	self::$connection->close();
    }
}
?>
