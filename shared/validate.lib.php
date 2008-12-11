<?php
function validate(){
//empty all the http request variables
//---
$req= array();
	  foreach($_GET as $k=>$v){
			$req[$k]=$v;
			unset($_REQUEST[$k]);
	  }
	  foreach($_POST as $k=>$v){
			$req[$k]=$v;
			unset($_REQUEST[$k]);
	  }
	  unset($_GET);
	  unset($_POST);
//---
	if ( isset( $req ) ){
			foreach($req as $k=>$v){
				if(is_string($v)){
						$allowedTags='<a><br><b><h1><h2><h3><h4><i>' .
								'<img><li><ol><p><strong><table>' .
								'<tr><td><th><u><ul>';
						strip_tags($v,$allowedTags);
						htmlentities($v);
						addslashes($v);
					}
				}
		}
	return $req;
}
?>
