<?php
/*
	  @name          acp [controller]
	  @filename      acp.c.php
	  @description   acp controller class
	  Generated By Scaffolding Script
	  handels the following cases:
	  [auth]
	  [out ]
	  @author   AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
*/
if(!isset($iWasThere)){
	  echo ("bang bang !");
}

Load()->control('control');

class acp extends ctrl{
	function __construct($req) {
		parent::__construct($req);
		global $iWasThere;
		$this->req = $req;
		$this->name = 'acp';
	}

	function _default() {
		if ($this->is_admin()) {
			$adminView['header']  = "<h1>Admin Control Panel</h1>";
			$adminView['content'] = easy_render($this->name, 'functions');
			$adminView['info']    = "<a href='" . RUN_PATH . "/acp'>Admin Control Panel</a>";
			$this->html          .= render($this->name, 'acp', $adminView);
		} else {
			$gstView['content'] = easy_render($this->name, 'login');
			$gstView['info']    = "Authentication Required";
			$gstView['header']  = "<h1>Admin Control Panel</h1>";
			$this->html 	   .= render($this->name, 'acp', $gstView);
		}
	}
	
	function auth() {
		if($this->req['username'] == "admin" && $this->req['password'] == "3mates"){
			  $_SESSION['admin'] = P_ADMIN;
			  $this->html 		.= $this->goto(RUN_PATH . "/acp", "Successful Login ..", 0);
		}else{
			  $this->html .= $this->goto(RUN_PATH . "/acp", "WRONG log in information,try again...");
		}
	}

	function out() {
		if ($this->is_admin()) {
			  unset($_SESSION['admin']);
			  $this->html .= $this->goto(RUN_PATH . "/acp", "You have been logged out");
		}
	}

}
?>