<?php
define( 'INSTALL_PATH',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
define( 'RUN_PATH',"http://".$_SERVER['SERVER_NAME'].str_replace('index.php','index',$_SERVER['SCRIPT_NAME']));
define( 'LINK',str_replace('/index','',RUN_PATH));
define( 'SITE_NAME',"7" );
define( 'AUTHUR',"El-Sayed G. AbdulAzem" );
define( 'VERSION',"0.1");
?>