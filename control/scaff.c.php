<?php
/*************///scaffloding class//************
/********************************************
     @name     scaffolding
     @filename scaff.c.php
     @description   Scaffolding a table
     @author   AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
*********************************************/

$iWasThere=true;

Load()->control('control');

class scaff extends ctrl {
    private $table;

	function __construct($req) {
     	parent::__construct($req);
     	$this->req = $req;
     	$this->name = 's';

     	if (!$req['wt']) {
     		exit("ERROR: no controller: " + $req['wt']);
     	}
     	$this->table = $this->req['wt'];

     	if (SCAFF != $this->table) {
	     	exit("CAN't scaff");
     	}

     	$this->index();
     }

     function index(){
		  $dir = "view/default/" . $this->table;
		  if (!is_dir($dir) && !mkdir($dir, 0777)) {
		  	die("Coudn't create views directory: " . $this->table);
		  }

		  $this->html .= "Create Directory [<i>{$this->table}</i>] : <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />";

		  $this->html .= $this->touch_view_files();

          $qDescribeTable = "describe {$this->table}";
          $result 		  = query($qDescribeTable);
          while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {

	          if($row['Key'] != "PRI"){
		            $fields_in_set[] = $this->add_to_set($row);
		            $fields_in_req[] = $this->add_to_req($row);
		            $fields[]	    = $row['Field'];
		            $this->check_type($row, $elements);
	          }
          }

          $elements['show']	= $this->create_user_show($fields);
          $elements['ashow']	= $this->create_admin_show($fields);

          $this->html .= $this->write_view_files($elements);

          $this->html .= $this->write_model_file(array('set'=>$fields_in_set, 'req'=>$fields_in_req, 'col'=> $columns));

          $this->html .= $this->write_controller_file();

          $this->html .=  "your are now ready to browse <a href='".RUN_PATH."/{$this->table}'>{$this->table}</a> ";

	 }


      function touch_view_files(){
       		$verbose = "";
       		$views = array(
       						"{$this->table}/a.{$this->table}.v.gam",
       						"{$this->table}/{$this->table}.v.gam",
       						"{$this->table}/add.{$this->table}.v.gam",
       						"{$this->table}/edit.{$this->table}.v.gam"
       					  );

       		foreach($views as $view) {
	       		if (touch("view/default/{$view}")) {
					$verbose .= "Touch View File [<i>{$view}</i>] : <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />";
				} else {
					$verbose .= "Touch View File [<i>{$view}</i>] : <span style='color:red;font-family:verdana;font-weight:bold'>failed</b><br />";
				}
       		}

			return $verbose;
	  }

	  function add_to_set($row){
		  return sprintf("'`%s`' => \$req['%s']", $row['Field'], $row['Field']);
	  }

	  function add_to_req($row){
		  return $this->add_to_set($row);
	  }

	  function create_user_show($fields){
			foreach ($fields as &$v)
			{
				  $v = "{".$v."}";
			}
			$html = "<table>
					 <tr>
						  <td>" . implode("</td>\n\t\t<td>", $fields) . "</td>
					 </tr>
				    </table>";
			return $html;
	  }

	  function create_admin_show($fields){
			foreach ($fields as &$v)
			{
				  $v = "{".$v."}";
			}
			$html="
				  <table style='width:100%'>
						<tr>
							  <td>" . implode("</td>\n\t\t<td>", $fields) . "</td>
							  <td style='text-align:right'>
								  <a href='" . RUN_PATH . "/{$this->table}/ed/{id}'>
										edit
								  </a> --
								  <a href='javascript: confirm_rm({id},\"{$this->table}\")'>
										remove
								  </a>
							  </td>
						</tr>
				  </table>";
			return $html;
	  }

	  function check_type($row, &$elements){

			$label = $this->cleanLabel($row['Field']);

			switch($row['Type']){
				  case 'text':
					  $elements['add'] .= "<label for='{$row['Field']}'>{$label}:</label><br />
					  		<textarea id='{$row['Field']}'  name='{$row['Field']}'></textarea>
								<script language=\"javascript1.2\">
									generate_wysiwyg('{$row['Field']}');
								</script>
					  		<br />\n";
					  $elements['edit'] .= "{$label}:<br />
					  		<textarea id='{$row['Field']}'  name='{$row['Field']}'>{{$row['Field']}}</textarea>
								<script language=\"javascript1.2\">
									generate_wysiwyg('{$row['Field']}');
								</script>
					  		<br />\n";
					break;
				  default:
					  $elements['add']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='text' name='{$row['Field']}' /><br />\n";
					  $elements['edit']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='text' name='{$row['Field']}' value='{{$row['Field']}}' /><br />\n";
					break;
			}
	  }

	  function write_model_file($model_data){
			$model = "./model/{$this->table}.php";

			if(is_file($model) || touch($model))
			{
				  $arData['table'] 			= $this->table;
				  $arData['updates'] 		= implode(", \n\t\t\t\t\t", $model_data['set']);
				  $arData['insert_values']	= implode(", \n\t\t\t\t\t", $model_data['req']);

				  if (file_put_contents($model, render($this->name, 's.model', $arData))) {
				  	return $this->outMsg("Create Model", "done");
				  }

			}
			return $this->outMsg("Create Model", "failed");


	  }

	  function write_controller_file(){
			$arData['table']=$this->table;
			$cntrl = "./control/{$this->table}.c.php";
			$verbose = "";

			if(is_file($cntrl) || touch($cntrl))
			{
				  if (file_put_contents($cntrl, render($this->name, 's.controller', $arData))) {
				  		$this->write_controller_link('acp/miniOptions');
				  		$this->write_controller_link('acp/functions');
				  		$verbose = $this->outMsg("Create Controller", "done");
				  }

			} else {
				$verbose = $this->outMsg("Create Controller", "failed");
			}

			return $verbose;
	  }


	  function write_controller_link($file) {
	  	$optionsFile = INSTALL_PATH.'/view/' . THEME . '/' . $file . '.v.gam';
  		$stringHTML = file( $optionsFile );
  		$link = "<a class='icon' border=0 href='{RUN_PATH}/{$this->table}'>". ucfirst($this->table) . " Management</a> \n";
  		if (!stristr(implode(' ', $stringHTML), $this->table)) {
  			$out[0] = array_shift($stringHTML);
  			$out[1] = $link;
  			$out = array_merge($out, $stringHTML);
  			file_put_contents($optionsFile, $out);
  		}
	  }

	  function write_view_files($elements){
			file_put_contents("view/default/{$this->table}/{$this->table}.v.gam", $elements['show']);
			file_put_contents("view/default/{$this->table}/a.{$this->table}.v.gam", $elements['ashow']);

	        $arData=array('table' => $this->table
	                     ,'elements' => $elements['add']);

			file_put_contents("view/default/{$this->table}/add.{$this->table}.v.gam", open_and_render_var($this->name, 's.add',$arData));
			$arData['elements'] = $elements['edit'];
			file_put_contents("./view/default/{$this->table}/edit.{$this->table}.v.gam", open_and_render_var($this->name, 's.edit',$arData));

			return "Write view files[ {$this->table}/a.{$this->table}.v.gam , {$this->table}/{$this->table}.v.gam , {$this->table}/add.{$this->table}.v.gam and {$this->table}/edit.{$this->table}.v.gam  ]: <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />";
	  }


	  function cleanLabel($gluedStr){
	      return ucfirst(str_replace('_'," ", $gluedStr));
	  }

	  function outMsg($msg, $type) {
		  if ($type === "success" || $type === "done") {
			  $color = "green";
		  } else if ($type === "fail" || $type === "failed") {
			  $color = "red";
		  }
		  $out  = $msg . ": ";
		  $out .= "<span style='color:" . $color . ";font-family:verdana;font-weight:bold'>" . ucfirst($type) . "</span><br />";
		  return $out;
	  }
}
?>
