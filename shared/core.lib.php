<?php
function start($mode){
	global $iWasThere;
	require("shared/cleanurl.lib.php");
	$clean = new CleanURL;
	$clean->parseURL();
	$clean->setRelative('relativeslash'); //relativeslash is variable name
	$clean->setParts('do','wt','id');
	require( 'configure/system.cnf.php' );
	require( 'configure/db.cnf.php' );
	require( 'configure/theme.cnf.php' );
	require( 'configure/control.cnf.php' );
	switch($mode){
		case 'dev':
			//error_reporting(E_ERROR | E_PARSE );
			//error_reporting(E_ALL);
			break;
		case 'online':
			error_reporting(E_ERROR | E_PARSE );
			//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
			//error_reporting(0);
			break;
		}
	include( 'shared/validate.lib.php' );
	$req = validate( );
	$cntrl=$arInstldContrl['default'];
	$found=0;
	foreach( $arInstldContrl as $key => $value ){
		if ( $key == $req['do'] ){
				$cntrl = $value;
				$found=1;
			}
	}
	if($found){
		require( 'control/'.$cntrl.'.c.php' );
          $ctrl = new $cntrl($req);
          $html = $ctrl->html;
	}else {
		$_SESSION['sys_err']='Error-0001';
		require( 'control/'.$cntrl.'.c.php' );
//		$html=$cntrl($req);
          $ctrl = new $cntrl($req);
          $html = $ctrl->html;
	}
	return $html;
}
?>
