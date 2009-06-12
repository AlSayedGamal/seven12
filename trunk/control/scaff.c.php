<?php
/*************///scaffloding class//************
/********************************************
     @name     scaffolding
     @filename scaff.c.php
     @description   Scaffolding a table
     @author   AlSayed Gamal <mail.gamal@gmail.com> <http://www.egamal.com>
*********************************************/
//include('shared/render.lib.php') ;
//include('shared/db.lib.php') ;
$iWasThere=true;
include('control.php');
class scaff extends ctrl {
     var $table;
     function index($req){
          $this->table=SCAFF;
          $html.=$this->touch_view_files();
          $qDescribeTable="describe {$this->table}";
          $result=query($qDescribeTable);
          while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
	          if($row['Key'] != "PRI"){
		            $fields_in_set[]=$this->add_to_set($row);
		            $fields_in_req[]=$this->add_to_req($row);
		            $fields[]=$row['Field'];
		            $html.=$this->check_type($row,$elements);
	          }
          }
          $elements['show']=$this->create_user_show($fields);
          $elements['ashow']=$this->create_admin_show($fields);
          $html.=$this->write_view_files($elements);
          $html.=$this->write_controller_file();
          $html.=$this->write_model_file(array('set'=>$fields_in_set,'req'=>$fields_in_req));
          $html .= "your are now ready to browse <a href='".RUN_PATH."/{$this->table}'>{$this->table}</a> ";
          return $html;
	}

       function touch_view_files(){
			$verbose .=(touch("view/default/a.{$this->table}.v.gam"))? "Touch View File [<i>a.{$this->table}.v.gam</i>] : <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />" : "Touch View File [<i>a.{$this->table}.v.gam</i>] : <span style='color:red;font-family:verdana;font-weight:bold'>failed</b><br />";

			$verbose .=(touch("view/default/{$this->table}.v.gam"))? "Touch View File [<i>{$this->table}.v.gam</i>] : <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />" : "Touch View File [<i>{$this->table}.v.gam</i>] : <span style='color:red;font-family:verdana;font-weight:bold'>failed</b><br />";

			$verbose .=(touch("view/default/add.{$this->table}.v.gam"))? "Touch View File [<i>add.{$this->table}.v.gam</i>] : <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />" : "Touch View File [<i>add.{$this->table}.v.gam</i>] : <span style='color:red;font-family:verdana;font-weight:bold'>failed</b><br />";
			
			$verbose .=(touch("view/default/edit.{$this->table}.v.gam"))? "Touch View File [<i>edit.{$this->table}.v.gam</i>] : <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />" : "Touch View File [<i>edit.{$this->table}.v.gam</i>] : <span style='color:red;font-family:verdana;font-weight:bold'>failed</b><br />";
			return $verbose;
	  }
	  function add_to_set($row){

			return "`{$row['Field']}` = '{\$req['{$row['Field']}']}'";
	  }

	  function add_to_req($row){
			return "'{\$req['".$row['Field']."']}'";
	  }

	  function create_user_show($fields){
			foreach ($fields as &$v)
			{
				  $v = "{".$v."}";
			}
			$html="<table>
									<tr>
										  <td>".implode("</td>\n\t\t<td>",$fields).
									"		</td>
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
							  <table>
									<tr>
										  <td>".implode("</td>\n\t\t<td>",$fields).
									"	</td>
										  <td>
										  <a href='".RUN_PATH."/{$this->table}/ed/{id}'>
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

	  function check_type($row,&$elements){

			$this->cleanField($row['Field']);

			switch($row['Type']){
				  case 'text':
							  $elements['add'].="{$row['Field']}:<br /> 
							  		<textarea id='{$row['Field']}'  name='{$row['Field']}'></textarea>
										<script language=\"javascript1.2\">
											generate_wysiwyg('{$row['Field']}');
										</script>
							  		<br />\n";
							  $elements['edit'].="{$row['Field']}:<br /> 
							  		<textarea id='{$row['Field']}'  name='{$row['Field']}'>{{$row['Field']}}</textarea>
										<script language=\"javascript1.2\">
											generate_wysiwyg('{$row['Field']}');
										</script>
							  		<br />\n";
						break;
				  default:
							  $elements['add'].="{$row['Field']}: <input type='text' name='{$row['Field']}' /><br />\n";
							  $elements['edit'].="{$row['Field']}: <input type='text' name='{$row['Field']}' value='{{$row['Field']}}' /><br />\n";
						break;
			}
	  } 
	  function write_model_file($model_data){

			if(is_file("./model/{$this->table}.php"))
			{
				  $arData['table']=$this->table;
				  $arData['updates']=implode(',',$model_data['set']);
				  $arData['insert_values']=implode(",",$model_data['req']);
				  file_put_contents("./model/{$this->table}.php",render('s.model',$arData));
			}else{
				  return "Create Model : <span style='color:red;font-family:verdana;font-weight:bold'>Failed</span><br />";
			}

	  }

	  function write_controller_file(){
			$arData['table']=$this->table;

			if(is_file("./control/{$this->table}.c.php"))
			{
				  $verbose=(file_put_contents("./control/{$this->table}.c.php",render('s.controller',$arData)))  ? "Create Controller : <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />" :"Create Controller : <span style='color:red;font-family:verdana;font-weight:bold'>Failed</span><br />" ;
				  return $verbose;
			}

			return "Create Controller : <span style='color:red;font-family:verdana;font-weight:bold'>Failed</span><br />" ;
	  }

	  function write_view_files($elements){
			file_put_contents("view/default/{$this->table}.v.gam",$elements['show']);
			file_put_contents("view/default/a.{$this->table}.v.gam",$elements['ashow']);
	          $arData=array('table'=>$this->table
	                         ,'elements'=>$elements['add']);
			file_put_contents("view/default/add.{$this->table}.v.gam",render('s.add',$arData));
			$arData['elements']=$elements['edit'];
			file_put_contents("./view/default/edit.{$this->table}.v.gam",render('s.edit',$arData));
			return "Write view files[ a.{$this->table}.v.gam , {$this->table}.v.gam , add.{$this->table}.v.gam and edit.{$this->table}.v.gam  ]: <span style='color:green;font-family:verdana;font-weight:bold'>done</span><br />";
	  }


	  function cleanField(&$gluedStr){
	      $gluedStr=ucfirst(str_replace('_'," ", $gluedStr));
	  }	
}
?>
