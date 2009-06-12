<?php
function add_curly( &$key ){
	  $key = '{'.$key.'}';
}
function render( $key, $param = 'no' ){
	  $out = easy_render($key);
	  $out = render_var( $out, $param );
	  return $out;
}
function easy_render( $key ){
//		echo  INSTALL_PATH.'/view/'.THEME.'/'."$key".'.v.gam' ; // trace view files
	  $stringHTML = file_get_contents( INSTALL_PATH.'/view/'.THEME.'/'."$key".'.v.gam' );
	  $stringHTML=basic_render($stringHTML);
	  return $stringHTML;
}
function render_var( $stringHTML, $param='no' )
{
	  
	  if( is_array( $param ) ){
			$keys=array_keys( $param );
			
	  array_walk( $keys, 'add_curly' );
	  $stringHTML = str_ireplace( $keys,array_values($param), $stringHTML );
	  }
	  return $stringHTML;
}
function basic_render($stringHTML){
	  $constants=array(
			"{LINK}" => LINK,
			"{RUN_PATH}" => RUN_PATH
	  );
	  return str_replace( array_keys($constants),array_values($constants), $stringHTML );
}
?>
