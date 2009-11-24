<?php

// System wide paths
//define( 'SYS_PATH',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));


define( 'INSTALL_PATH', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']) );

define( 'RUN_PATH'	  	, "http://" . $_SERVER['SERVER_NAME'] );
define( 'LINK' 	  		, RUN_PATH );

define( 'UPLOAD_PATH'	, INSTALL_PATH . 'uploads/' );

// site-specific information
define( 'SITE_NAME' 	, "Seven12" );
define( 'VERSION'		, "0.0");
define( 'AUTHUR'		, "" );
define( 'WEB_ADMIN_MAIL', "");

define( 'P_ADMIN'		, 1 );
define( 'P_USER'		, 2 )
?>
