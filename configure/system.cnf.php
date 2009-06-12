<?php
define( 'UPLOAD_PATH',$_SERVER['DOCUMENT_ROOT'].'/uploads/');
//define( 'SYS_PATH',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
define( 'INSTALL_PATH',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
define( 'RUN_PATH',"http://".$_SERVER['SERVER_NAME'].str_replace('index.php','index',$_SERVER['SCRIPT_NAME']));
define( 'LINK',str_replace('/index','',RUN_PATH));
define( 'SITE_NAME',"SCS | Middle East" );
define( 'AUTHUR',"El-Sayed G. AbdulAzem" );
define( 'VERSION',"0.1");
define( 'P_ADMIN',1);
define('WEB_ADMIN_MAIL',"mail.gamal@gmail.com");
?>
