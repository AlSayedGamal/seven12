<?php
/*************///scaffloding class//************
/********************************************
     @name     		scaffolding
     @filename 		scaff.c.php
     @description   Scaffolding a table
     @author   		AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
*********************************************/

$iWasThere = true;

Load()->control('control');
Load()->lib('Exception');

class scaff extends ctrl {
	
	private $table;
	private $fields = array();
	private $enctype = "";
	private $primaryKey;

	function __construct($req) {
		
     	parent::__construct($req);
		
     	$this->req = $req;
     	$this->name = 's';

     	if (!$req['wt']) {
     		exit("ERROR: no controller: " + $req['wt']);
     	}

     	$this->table = $this->req['wt'];
/*
     	if (SCAFF != $this->table) {
	     	exit("CAN't scaff");
     	}
*/
     	$this->index();
		
     }
	 

     function index() {
     	
     	Load()->lib('db');

        $qDescribeTable = "describe {$this->table}";
        $result 		  = query($qDescribeTable);
		
		$elements = array('add'=> '', 'edit'=>'', 'type'=>'');
		  
	    while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	    	
		    if($row['Key'] != "PRI"){
		    	
		       $fields[]	    = $row['Field'];
		       $this->check_type($row, $elements);
			   
		    } else {
		    	
		    	$this->primaryKey = $row['Field'];
				
		    }
			
	    }
	
		$this->fields = $fields;
     
          // if you want to scaff a model only
         if (isset($this->req['type']) and $this->req['type'] == "model") {
          	
          	$this->html .= $this->write_model_file();
			
         } else {
          	
 		  	$this->html .= $this->write_model_file();
		     
	    	$elements['show']		= $this->create_user_show($fields);
         	$elements['ashow']		= $this->create_admin_show($fields);
         	$elements['miniAShow'] 	= $this->create_mini_show($fields);
	     
		    $this->html .= $this->write_view_files($elements);

		    $this->html .= $this->write_controller_file();
			 
		}

        $this->html .=  "your are now ready to browse <a href='" . 
						RUN_PATH . "/{$this->table}'>{$this->table}</a> ";

	}
	


	function create_user_show($fields){
		
		foreach ($fields as &$v)	{
			  $v = "{".$v."}";
		}
		
		$html = "<table>
				 <tr>
					  <td>" . implode("</td>\n\t\t<td>", $fields) . "</td>
				 </tr>
			    </table>";
				
		return $html;
	}

	// will be written to a.{table}.all.v.html : table with header
	function create_mini_show($fields) {
		
		foreach ($fields as &$v)	{
			  $v = ucfirst($v);
		}
		
		$html = "<table style='width:100%'>
				 <tr style='text-align:left'>
					  <th>" . implode("</th>\n\t\t<th>", $fields) . "</th>
					  <th style='text-align:right'>Action</th>
				 </tr>
				 {data}
			    </table>";
				
		return $html;
	}
	

	function create_admin_show($fields){
		
		foreach ($fields as &$v)	{
			  $v = "{".$v."}";
		}
		
		$html = "
				<tr>
				  <td>" . implode("</td>\n\t\t<td>", $fields) . "</td>
				  <td style='text-align:right'>
					  <a href='{RUN_PATH}/{$this->table}/ed/{id}'>
							edit
					  </a> --
					  <a href='javascript: confirm_rm({id},\"{$this->table}\")'>
							remove
					  </a>
				  </td>
				</tr>";
				
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
				if ($row['Field'] === "password" or $row['Field'] === "passwd") {
					
					$elements['add']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='password' name='{$row['Field']}' /><br />\n";
				  	$elements['edit']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='password' name='{$row['Field']}' value='{{$row['Field']}}' /><br />\n";
					
				} else if (strpos($row['Field'], "file") !== FALSE) {
					
					$elements['add']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='file' name='{$row['Field']}' /><br />\n";
				  	$elements['edit']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='file' name='{$row['Field']}' value='{{$row['Field']}}' /><br />\n";
				  	$this->enctype = "enctype='multipart/form-data'";
					
				} else {
					
					$elements['add']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='text' name='{$row['Field']}' /><br />\n";
					$elements['edit']	.= "<label for='{$row['Field']}'>{$label}:</label><input id='{$row['Field']}' type='text' name='{$row['Field']}' value='{{$row['Field']}}' /><br />\n";
					
				}
				
			break;

		}
	}
	

	function write_model_file(){
		
		$model = "./model/{$this->table}.php";

		if (is_file($model) || touch($model)) {
			
			  $arData['table'] 			= $this->table;
			  $arData['primary_key']	= $this->primaryKey;
			  $arData['fields']			= "'" . implode("', '", $this->fields) . "'";

			  if (file_put_contents($model, render($this->name, 's.model', $arData))) {
			  		return $this->outMsg("Create Model", "done");
			  }

		}

		chmod($model, 0646);

		return $this->outMsg("Create Model", "failed");
	}


	function write_controller_file() {
		
		$arData['table'] = $this->table;
		$cntrl = "./control/{$this->table}.c.php";
		$verbose = "";

		if (is_file($cntrl) || touch($cntrl)) {
			  if (file_put_contents($cntrl, render($this->name, 's.controller', $arData))) {
			  		$this->write_controller_link('acp/miniOptions');
			  		$this->write_controller_link('acp/functions');
			  		$verbose = $this->outMsg("Create Controller", "done");
			  }
		} else {
			$verbose = $this->outMsg("Create Controller", "failed");
		}

		chmod($cntrl, 0646);

		return $verbose;
	}


	function write_controller_link($file) {
		
	  	$optionsFile = INSTALL_PATH . 'view/' . THEME . '/' . $file . '.v.html';

	  	try {
	  		
		  	if (!is_writeable($optionsFile)) {
		  		throw new Seven12_Exception("Can NOT write to : " . $optionsFile);
		  	}
			
		} catch (Exception $e) {
			echo $e->getError();
		}

  		$stringHTML = file( $optionsFile );
  		$link = sprintf("<a class='icon' border=0 href='{RUN_PATH}/%s'>%s</a> | \n", 
							$this->table, ucfirst($this->table));
		
  		if (!stristr(implode(' ', $stringHTML), $this->table)) {
  			
  			$out[0] = array_shift($stringHTML);
  			$out[1] = $link;
  			$out = array_merge($out, $stringHTML);
  			file_put_contents($optionsFile, $out);
			
  		}
		
	}


	function write_view_files($elements){
		
	  	$dir = "view/default/" . $this->table;
	  	if (!is_dir($dir) && !mkdir($dir, 0777)) {
			die("Coudn't create views directory: " . $this->table);
		}
		$this->html .= $this->outMsg("Create Directory [<i>{$this->table}</i>]", "done");

	  	$views = array(
					"view" => "view/default/{$this->table}/{$this->table}.v.html",
					"a" 	  => "view/default/{$this->table}/a.{$this->table}.v.html",
					"miniA"=> "view/default/{$this->table}/a.{$this->table}.all.v.html",
					"add"  => "view/default/{$this->table}/add.{$this->table}.v.html",
					"edit" => "view/default/{$this->table}/edit.{$this->table}.v.html"
  				);

		file_put_contents($views['view'], $elements['show']);
		file_put_contents($views['a'], $elements['ashow']);
		file_put_contents($views['miniA'], $elements['miniAShow']);

		$arData = array('table' => $this->table
		                ,'elements' => $elements['add']
		                ,'enctype' => $this->enctype);

		file_put_contents($views['add'], open_and_render_var($this->name, 's.add',$arData));
		$arData['elements'] = $elements['edit'];
		file_put_contents($views['edit'], open_and_render_var($this->name, 's.edit',$arData));

		foreach ($views as $k => $v) {
       		chmod($v, 0646);
       	}

		$msg = "Write view files[ {$this->table}/<strong>{</strong>a.{$this->table}.v.html,{$this->table}.v.html,add.{$this->table}.v.html,edit.{$this->table}.v.html<strong>}</strong> ]";
		return $this->outMsg($msg, "done");
		
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
		$out  = $msg . " : ";
		$out .= "<span style='color:" . $color . ";font-family:verdana;font-weight:bold'>" . ucfirst($type) . "</span><br />";
	  return $out;
	}
	
}
?>
