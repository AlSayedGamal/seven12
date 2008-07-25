<?php
$gConnect;
/*function query($query,$ifNot='',$msg='')
{
$gConnect = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die(mysql_error() . 'connection error !');
mysql_select_db(DB_NAME,$gConnect) or die(mysql_error());
		$result=mysql_query($query,$gConnect) or die( "<font style='font-size:10; font-weight:bold; color:#FF0000'>Error: $ifnot(". mysql_error().")</font>");
		return $result;
		echo $msg;
}
*/
function query($q,$success="",$fail="")
{
	$gConnect = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die("Error connecting to mysql server") ;
	mysql_select_db(DB_NAME,$gConnect) or die("Error connecting to mysql database") ;
	$result=mysql_query($q,$gConnect) or die("Error Excuting Query: $fail <pre>" . $q. "</pre><br />".mysql_error()) ;
	echo $success;
	return $result;
}
?>