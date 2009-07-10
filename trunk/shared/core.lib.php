<?php
require('shared/loader.lib.php');


function &Load ()
{
    static $instance;

    if (!is_object($instance)) {
        // does not currently exist, so create it
        $instance = new Loader;
    }

    return $instance;

}

function start($mode){
	global $iWasThere;

	require( 'configure/system.cnf.php' );
	require( 'configure/db.cnf.php' );
	require( 'configure/theme.cnf.php' );
	require( 'configure/control.cnf.php' );

	//$load = Loader::getInstance();

	Load()->lib('cleanurl');

	$clean = new CleanURL;
	$clean->parseURL();
	$clean->setRelative('relativeslash'); //relativeslash is variable name
	$clean->setParts('do','wt','id');

	switch($mode){
		case 'dev':
			//error_reporting(E_ERROR | E_PARSE );
			//error_reporting(E_ALL);
			//error_reporting( E_ALL & ~E_NOTICE );
			error_reporting(E_ERROR | E_PARSE);		
			break;
		case 'online':
			//error_reporting(E_ERROR | E_PARSE );
			//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
			//error_reporting(0);
			break;
		}

	Load()->lib('validate');

	$req = validate( );

	$cntrl = $arInstldContrl['default'];
	$found = 0;

	foreach( $arInstldContrl as $key => $value ){
		if ( $key == $req['do'] ){
				$cntrl = $value;
				$found=1;
		   }
	}

	if($found || $cntrl == "home"){
		Load()->control($cntrl);
		//require( 'control/' . $cntrl . '.c.php' );

		$ctrl = new $cntrl($req);

		if ($cntrl != "scaff") {
			$wt =( $req['wt'] ) ? $req['wt'] : "_default";

			if (method_exists($ctrl, $wt)) {
				$ctrl->$wt();
			} else {
				$ctrl->_default();
			}
		}

		$html  = $ctrl->html;
		$html .= $ctrl->footer();
	}else {
		$_SESSION['sys_err']='Error-0001';
		require( 'control/'.$cntrl.'.c.php' );
/* DEBUG: */
		echo "ERROR";

//		$html=$cntrl($req);
        $ctrl = new $cntrl($req); //   For example     $ctrl = new $users(array(....'do'=>'list'));
//
//        $ctrl = new $cntrl;
//	$html = $ctrl->$req['do']; // for example $users->list();
        $html = $ctrl->html;
	}
	return $html;
}
?>
