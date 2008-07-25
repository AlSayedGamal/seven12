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
     function index($req)
          {
               $this->table=SCAFF;               
touch("./view/default/a.{$this->table}.v.gam");
touch("./view/default/{$this->table}.v.gam");
touch("./view/default/add.{$this->table}.v.gam");
touch("view/default/edit.{$this->table}.v.gam");
               mysql_connect("localhost","root","");
               $qDescribeTable="describe {$this->table}";
             //  echo $qDescribeTable;
               mysql_select_db("scaf");
               $result=mysql_query($qDescribeTable);
               $verbose .="<table border=1>
                    <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                    </tr>";
                    while($row=mysql_fetch_array($result,MYSQL_ASSOC))
                    {
                         $fields[]="{".$row['Field']."}";
                    $inputs;
                    if($row['Key'] != "PRI")
                    {
                         $fields_in_set[]="`{$row['Field']}` = '{\$req['{$row['Field']}']}'";
                         $fields_in_req[]="'{\$req['".$row['Field']."']}'";
                         $field=$row['Field'];
                         if($row['Type'] == "text"){
                              $add.= "$field:<br /> <textarea name='{$row['Field']}'></textarea><br />\n";
                              $edit.="$field:<br /> <textarea name='{$row['Field']}'>{{$row['Field']}}</textarea> <br />\n";
                         }else{
                              $add.= "$field: <input type='text' name='{$row['Field']}' /><br />\n";
                              $edit.="$field: <input type='text' name='{$row['Field']}' value='{{$row['Field']}}' /><br />\n";
                         }
                         $show.="";
                    }
                    $verbose.="<tr>
                    \t\t<td>";
               $verbose.=implode("</td>\n\t\t<td>",$row) . "\n\t\t</td>\n\t</tr>";
               
                    }
                    $show="<table>\n\t<tr>\n\t<td>".implode("</td>\n\t\t<td>",$fields) ."\n\t\t</td>\n\t</tr>\n</table>";
                    //echo $show;
                    $ashow="<table>\n\t<tr>\n\t<td>".implode("</td>\n\t\t<td>",$fields) ."\n\t\t</td>\n\t\t<td>\n\t\t\t<a href='?do={$this->table}&wt=ed&id={id}'>edit</a> -- <a href='javascript: confirm_rm({id},\"{$this->table}\")'>remove</a>\n\t\t</td>\n\t</tr>\n</table>";
                    $arData['table']=$this->table;
                   
                    file_put_contents("view/default/a.{$this->table}.v.gam",$ashow); 

                    file_put_contents("view/default/{$this->table}.v.gam",$show);                    
                    $arData['elements']=$add;

                    file_put_contents("view/default/add.{$this->table}.v.gam",render('s.add',$arData));
                    $arData['elements']=$edit;

                    file_put_contents("./view/default/edit.{$this->table}.v.gam",render('s.edit',$arData));
                    $html.=$verbose."</table>\n<hr />";
                    
                    //create controller
                    //check the controller existance
                    if(is_file("./control/{$this->table}.c.php"))
                    {
                         file_put_contents("./control/{$this->table}.c.php",render('s.controller',$arData));
                    }
                     //create CRUD model
                     if(is_file("./model/{$this->table}.php"))
                    {
                         $arData['updates']=implode(',',$fields_in_set);
                         $arData['insert_values']=implode(",",$fields_in_req);
                         file_put_contents("./model/{$this->table}.php",render('s.model',$arData));
                    }
                    $html .= "<br />your are now ready to browse <a href='?do={$this->table}'>{$this->table}</a> ";
                  
               return $html;
	          }
}
?>

