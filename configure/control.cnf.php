<?php
define('SCAFF',"dr");
//NOTE:this array values cann't be the string 'scaff' OR 'Demo' these controller names are reserved for the framework basic installation.
//you can avoid this by removing these controllers if you don't need them. [after deployment]
$arInstldContrl=array(
          'default'=>'home'
          ,'s'=>'scaff'
	    ,'acp'=>'acp'
	    ,'reports'=>'reports'
	    ,'patients'=>'patients'
	    ,'dr'=>'dr'
	    ,'employee'=>'employee'
          ,SCAFF=>SCAFF
		);

?>
