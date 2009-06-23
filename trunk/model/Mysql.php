<?php
if(!$iWasThere)
     {
          die("go away loser");
     }

class MySQL {

	private static $connection = null;
	protected $host;
	protected $database;
	protected $username;
	protected $password;


     private $result 	= null;
     public $lastInsertID;

     private function __construct() { }

	public static function getInstance() {
		$conn = null;

	 	if (null === self::$connection) {
	 		$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($conn->connect_errno) {
				throw new Seven12_Exception(__METHOD__ . " Error connecting to mysql server ( " . $conn->connect_errno . " )<pre>"  . $conn->connect_error . "</pre>");
			}
			self::$connection = $conn;
		}

		return self::$connection;
	}

	public function getConnection() {
		return self::$connection;
	}

    function __destruct() {
	  	self::$connection->close();
    }
}
?>
