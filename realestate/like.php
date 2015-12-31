<?php
require_once('../lib.php');
require_once('lib.php');

user_login();
$id = param_get_int('id');
if (empty($id))
	return;
$cmd = param_get('cmd');
if ($cmd === 'on')
	realestate_like($id);
else if ($cmd === 'off')
	realestate_unlike($id);
?>
