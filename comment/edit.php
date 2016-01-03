<?php
require_once('../lib.php');
require_once('../json.php');

user_require_login();
$id = param_get_int('id');
$cmd = param_get('cmd');
if (empty($id) || $cmd !== 'add' /* XXX */)
        json_respond(false, 'invalidargument');
$comment = param_get_html('comment');
if (empty($comment))
	json_respond(false, 'emptydata');

$values = array(
    'realestate' =>	$id,
    'user' =>		$USER->id,
    'comment' =>	$comment
    );

$rc = db_record_insert('comment', $values);
if ($rc === false)
	json_respond(false, 'dberror');

json_respond(true);
?>
