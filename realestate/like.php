<?php
require_once('../lib.php');
require_once('../json.php');
require_once('lib.php');

user_login();
$id = param_get_int('id');
if (empty($id))
	json_respond(false, 'invalidargument');
$cmd = param_get('cmd');
if ($cmd === 'on')
	$rc = realestate_like($id);
else if ($cmd === 'off')
	$rc = realestate_unlike($id);
if ($rc !== false && ($r = realestate_get($id)) !== false)
	$likes = $r['likes'];
else
	$likes = 0;
json_respond($likes);
?>
