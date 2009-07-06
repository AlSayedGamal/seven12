<?php
function add_curly( &$key ){
	  $key = '{'.$key.'}';
}
function render( $name, $key, $param = 'no' ){
	  $out = easy_render($name, $key);
	  $out = render_var( $out, $param );
	  return $out;
}
function easy_render( $name = '.', $key ){
//		echo  INSTALL_PATH.'/view/'.THEME.'/'."$key".'.v.gam' ; // trace view files
	if ($name == ".") {
		$name = "";
	} else {
		$name = $name . '/';
	}

	  $stringHTML = file_get_contents( INSTALL_PATH.'/view/'.THEME.'/'. $name . "$key".'.v.gam' );
	  $stringHTML=basic_render($stringHTML);
	  return $stringHTML;
}

function open_and_render_var( $name = '.', $key, $param = 'no' ) {
	if ($name == ".") {
		$name = "";
	} else {
		$name = $name . '/';
	}

	  $stringHTML = file_get_contents( INSTALL_PATH . '/view/' . THEME . '/' . $name . "$key" . '.v.gam' );
	  $stringHTML = render_var($stringHTML, $param);

	  return $stringHTML;
}

function render_var( $stringHTML, $param='no' )
{
	if (is_object($param)) {
		$param = get_object_vars($param);
	}

	if( is_array( $param ) ){
		$keys=array_keys( $param );

		array_walk( $keys, 'add_curly' );
		$stringHTML = str_ireplace( $keys,array_values($param), $stringHTML );
	}
	return $stringHTML;
}

function basic_render($stringHTML){
	  $constants = array(
	  		"{SITE_NAME}" 	=> SITE_NAME,
			"{LINK}" 		=> LINK,
			"{RUN_PATH}" 	=> RUN_PATH
	  );
	  return str_replace( array_keys($constants),array_values($constants), $stringHTML );
}

?>
