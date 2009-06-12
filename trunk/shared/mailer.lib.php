<?php
function ht_mail($to,$subject,$message,$sender,$sender_title){
// multiple recipients
if(is_array($to)){
	foreach($to as $k => $v){
		$cTo=",$v";
	}
	$to=str_str($cTo,1);
}

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

// Additional headers
$headers .= "From: $sender_title <$sender>" . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);
}
?>
