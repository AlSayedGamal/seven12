<?php
if(!isset($iWasThere))
{
	die("bang bang !");
}
/**************class**ctrl*****************
     @name     ctrl() controller
     @description
                    the controller class to be inherted to each controller class
                    contains the main controller tasks and includes
                    -process the suitable model actions
                    -prepares the suitable view to be response
     @return   none:
     @author   AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
******************************************/

Load()->lib('render');

class ctrl {
    private $name = "";
    private $req  = array();

    function __construct(){}
/*****************************************
     @param    string $function case name
     @name     route_processor()
     @description
                    functions creates the url associated to the string input
                    ex:
                    route_processor('case');//will return "?do=ctrl&wt=case"
     @return   String URL that will trigger the function associated to the case
     @author   AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
******************************************/
     function route_processor($function)
          {
	          $funPath = "/{$this->name}/$function";
	          return $funPath;
          }
/*****************************************
     @param    string    $path   : the path that will be redirected to
               string    $msg    : the message will appear while redirecting to the path
               integer   $seconds  : the number of seconds the message will appear before redirecting to the path

     @name
     @description   A javascript-based redirection function that will redirect your to [$path argument]
                    and will show the [$msg argument] while redirecting; message duration will be [$seconds argument]seconds
     @return   String    HTML&Javascript required to be sent to the [response] presentation layer to redirect.
     @author   AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
******************************************/

///////////////////////////////////////////////////
	function goto($path,$msg="redirecting",$seconds=2) {
		if ($seconds === 0) {
			header("Location: " . $path);
		}

		$data = array(
					'message' => $msg,
					'path'	=> $path,
					'seconds' => $seconds
				);
		$html = render('.', 'redirect', $data);
		
		return $html;
	}

	function is_admin() {
		if (isset($_SESSION['admin']) && $_SESSION['admin'] == P_ADMIN) {
			  return true;
		}
		return false;
	}
	  /*
	  * @desccription: creates the HTML for the admin panel
	  * @param $shrt: Short name of the controller
	  * @param $shrt: Title to be added to control panel
	  * @return HTML needed to create the view
	  */
	 function admin_view($shrt, $title){
		$miniOptions = easy_render("acp", 'miniOptions');
		$data = array(
				'shrt' => $shrt,
				'title' => $title,
				'miniOptions' => $miniOptions
				);
		$arData = array(
				'info' => render('acp', 'header', $data),
			   	'header'=>"<h1>Admin Control Panel</h1>"
			   	);
		$header = render("acp", 'main', $arData);
		return $header;
	 }

	 function footer() {
	 	return ($this->is_admin()) ? easy_render('.', 'footer') : easy_render('.', 'usrftr');
	 }

}//class end
?>
