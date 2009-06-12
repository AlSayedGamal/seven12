<?php
/*
	  @name          news [controller]
	  @filename      news.c.php
	  @description   news controller class
	  Generated By Scaffolding Script
	  handels the following cases:
	  [add ]
	  [show]
	  [edit]
	  [rm  ]
	  @author   AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
*/
if(!isset($iWasThere)){
	  echo ("bang bang !");
}
require('control.php');
class acp extends ctrl{
	  function index($req){
			global $iWasThere;
			$wt=( $req['wt'] )?$req['wt']:"default";
			switch($wt){
				  case 'auth':
					  if($req['username']=="admin" && $req['password']=="3mates"){
							  $_SESSION['admin']=1;
							  $html.=$this->goto(RUN_PATH."/acp","welcome back");
						}else{
							  $html.=$this->goto(RUN_PATH."/acp","wrong log in information,try again...");
						}
						break;
				  case 'out':
					  if($_SESSION['admin']){
							  $_SESSION['admin']=0;
							  $html.=$this->goto(RUN_PATH."/acp","You have been logged out");
						}
						break;
				  default:
						if($_SESSION['admin']==1)
							  {
									$adminView['header']="<h1>Admin Control Panel</h1>";
									$adminView['content']=easy_render('functions');
									$adminView['info']="<a href='".RUN_PATH."/acp'>Admin Control Panel</a>";
									$html.=render('acp',$adminView);
							  }else{
									$gstView['content']=easy_render('login');
									$gstView['info']="Authentication Required";
									$gstView['header']="<h1>Admin Control Panel</h1>";
									$html.=render('acp',$gstView);
							  }
						break;
			  }
			return $html;
	  }
}
?>
