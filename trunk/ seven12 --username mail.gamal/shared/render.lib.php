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
	$out = file_get_contents( INSTALL_PATH.'/view/'.THEME.'/'."$key".'.v.gam' );
	return $out;
}
function render_var( $out, $param='no' )
{
	if( is_array( $param ) ){
		$keys=array_keys( $param );
		array_walk( $keys, 'add_curly' );
		$out = str_replace( $keys,array_values($param), $out );
	}
	return $out;
}
?>