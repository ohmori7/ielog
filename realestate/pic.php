<?php
require_once('../lib.php');
require_once('../json.php');
require_once('lib.php');

user_require_login();
$id = param_get_int('id');
$file = filename_clean(param_get('file'));
$r = realestate_get($id);
if (! realestate_is_editable($r))
	json_respond(false, 'permissiondenied');
$values = array('id' => $id, 'picture' => $file);
if (realestate_update($values) === false)
	json_respond(false, 'databaseerror');
json_respond(true);
?>
