<?php
define('IELOG_JSON',	true);

function
json_respond($value, $status = 'OK')
{

	echo(json_encode(array('status' => $status, 'results' => $value)));
	die();
}
?>
