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
class ctrl{
     var $name;
     var $html;
	function __construct($req)
	     {
		     include('shared/render.lib.php') ;
		     include('shared/db.lib.php') ;
		     $this->html=$this->index($req);
          }//ctrl()end
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
	          $funPath="?do={this->$name}&wt=".$function;
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
     function goto($path,$msg="redirecting",$seconds=3)
          {
	          $out .="$msg
	          <script>
	          function redir()
	          {
	          location.href='$path';
	          }
	          setTimeout('redir()',{$seconds}000);
	          </script>";
	          return $out;
          }//goto()end
     function index($req)
     {
               $this->name='controller name';
          //
     }
}//class end
?>
