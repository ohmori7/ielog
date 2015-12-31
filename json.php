<?php
define('IELOG_JSON',	true);

function
json_respond($value, $error = 'success')
{

	echo(json_encode(array('error' => $error, 'value' => $value)));
	die();
}
?>
