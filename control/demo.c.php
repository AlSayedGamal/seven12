<?php
include ('./control/control.php');
class demo extends ctrl{
     function index()
          {
               $html .="<img src='".RUN_PATH."/view/default/icon1.png' /><br />
               <p><u><center>this is the demo controller; you don't have controllers yet.<br /> please create one and change your default controller</u></p></center>";
               $html .="<p><b>To start scaffolding:<br/>
               1- Edit <i>/configure/db.cnf.php</i> with your server connection data.<br />
               2- Edit  <i>/configure/control.cnf.php</i> change constant <i>SCAFF</i> value and to your real table name instead of the \"your_table_name\" value.<br />
               3- Now create the following files:<br />
               <i>yourtable.c.php in the /control directory</i><br />
               <i>yourtable.php in the /model directory</i></b>
               <sub>make sure that your server will have <font color=red>write</font> permission on them.</blink><br /><b>
               after that click <a href='".RUN_PATH."/s'>here</a>";
               
               return $html;
          }//EO index
}
?>
