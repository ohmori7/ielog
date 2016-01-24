<?php
require_once('../lib.php');
require_once('lib.php');
require_once('../comment/lib.php');

user_require_login();
$id = param_get_int('id');
$r = realestate_get($id);
if (! realestate_is_editable($r))
	error('編集する権限がありません．');
if (! file_delete(realestate_data_dirbase($id)))
	error('ファイルを削除できません．');
if (! realestate_unlike_all($id) ||
    ! comment_delete_all($id) ||
    ! realestate_delete($id))
	error(db_error());
header_print(array(), 'list.php', IELOG_REDIRECT_TIMEOUT);
echo('削除しました．');
footer_print();
?>
