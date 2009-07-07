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

Load()->control('control');

class home extends ctrl{
	function __construct($req) {
		parent::__construct($req);
		global $iWasThere;
		$this->req = $req;
		$this->name = 'home';

		$this->html .= "<script>
						var LINK = '".LINK."';
			</script>";
		$this->html .= "<script src='".LINK."/view/default/scripts/js.js'></script>";
		$this->html .= "<script src='".LINK."/view/default/scripts/wysiwyg.js'></script>";

	}

	function _default() {
		$arData = array(
			'content' => easy_render('.', 'usrlogin')
		);
		$this->html .= render($this->name, 'home', $arData);
	}
	
	function about() {
		$arData['content']=easy_render($this->name, 'about');
		$this->html .= render($this->name, 'home',$arData);
	}

	function contact() {
		$arData['content']=easy_render($this->name, 'contact');
		$this->html .= render($this->name, 'home',$arData);
	}

	function send() {
		include("shared/mailer.lib.php");
		$message="<pre>
				Message time: ".date('M d Js h:i:s')."
				Sender: {$this->req['senderMail']}
				Sender email: {$this->req['email']}
				Sender Message:
				".nl2br($this->req['msg'])."
			</pre>";
			
		ht_mail(WEB_ADMIN_MAIL,"Contact inquery",$message,'no-reply@scs-me.com',"contact-us form");
		$thankYou.=$this->goto(RUN_PATH,easy_render('.', 'thankyou'));
		$this->html .= render('.', 'home',array('content'=>$thankYou));
	}

	function footer() {
		$this->html .= ($this->is_admin())? easy_render('.', 'footer') : easy_render('.', 'usrftr');
	}

}//EO home
?>
