<?php
if(!$iWasThere)
     {
          die("go away loser");
     }
class model{
     var $connection;
     var $result;
     function __construct()
     {
          $this->result="false";
          $this->connection = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die("Error connecting to mysql server<pre>".mysql_error()."</pre>") ;
	     mysql_select_db(DB_NAME,$this->connection) or die("Error connecting to mysql database<pre>".mysql_error()."</pre>") ;
     }
     function query($q,$success="",$fail="")
     {
	     $this->result=mysql_query($q,$this->connection) or die("Error Excuting Query: $fail <pre>" . $q. "</pre><br />".mysql_error()) ;
	     echo $success;
	     return $this -> result;
     }
}
?>
