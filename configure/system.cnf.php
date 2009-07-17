<?php

// System wide paths
//define( 'SYS_PATH',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
#define( 'UPLOAD_PATH' , $_SERVER['DOCUMENT_ROOT'] . '/uploads/' );
define( 'INSTALL_PATH', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']) );
define( 'UPLOAD_PATH', INSTALL_PATH . 'uploads/' );
define( 'RUN_PATH'	  , "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', 'index', $_SERVER['SCRIPT_NAME']) );
define( 'LINK' 	  , str_replace('/index', '', RUN_PATH) );

// site-specific information
define( 'SITE_NAME' 	, "tadbor" );
define( 'VERSION'		, "0.1" );
define( 'AUTHUR'		, "El-Sayed G. AbdulAzem" );
define( 'WEB_ADMIN_MAIL'	, "mail.gamal@gmail.com");

define( 'P_ADMIN'		, 1 );
define( 'P_USER'		, 2 )
?>
