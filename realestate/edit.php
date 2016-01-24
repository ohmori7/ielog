<?php
require_once('../lib.php');
require_once('../form.php');
require_once('lib.php');

user_require_login();

$form = new Form('realestateEditForm');
$form->addElement('hidden', 'id');
$form->addElement('header', null, '物件登録');
$form->addElement('textarea', 'abstract', '概要',
    array('cols' => 80, 'rows' => 5));
$form->addElement('textarea', 'description', '詳細な説明',
    array('cols' => 80, 'rows' => 10));
$form->addElement('select', 'contract', '契約形態', realestate_contracts());
$form->addElement('date', 'builtdate', '建築日', array(
    'size' => 50, 'maxlength' => 50,
    'minYear' => 1950, 'maxYear' => date('Y'),
    'format' => 'Ymd', 'addEmptyOption' => true,
    'emptyOptionText' => array('Y' => 'YYYY', 'm' => 'mm', 'd' => 'dd')));
$file =& $form->addElement('file', 'file', '外観の画像ファイル');
$form->addElement('text', 'zip', '郵便番号',
    array('size' => 16, 'maxlength' => 16));
$form->addElement('text', 'prefecture', '都道府県',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'city', '市町村',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'address', '住所',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('submit', null, '登録');

$form->addRule('abstract', '概要を入力して下さい．',
    'required', null, 'client');
$form->addRule('description', '詳細な説明を入力して下さい．',
    'required', null, 'client');
$form->addRule('contract', '契約形態を入力して下さい．',
    'required', null, 'client');
$form->addRule('builtdate', '建築日を入力して下さい．',
    'required', null, 'client');
$form->addRule('file', '外観の画像ファイルを入力して下さい．',
    'required', null, 'client');
$form->addRule('zip', '数字を入力して下さい．', 'numeric', null, 'client');

if ($form->isSubmitted() && $form->validate()) {
	if (! $file->isUploadedFile())
		error('inconsitent state!!');
	$values = $form->exportValues();
	$filename = $file->_value['name']; /* XXX */
	if (preg_match('/^.*(\.[^[\.]+)$/', $filename, $matches))
		$ext = $matches[1];
	else
		$ext = '';
	$filename = "pic$ext";
	$values['picture'] = $filename;
	if (empty($values['id']))
		$id = realestate_add($values);
	else if (realestate_is_editable(realestate_get($values['id'])))
		$id = realestate_update($values);
	else
		error('編集する権限がありません．');
	if ($id !== false) {
		$dir = realestate_data_dir($id);
		@mkdir($dir, 0700, true);
		$file->moveUploadedFile($dir, $filename);

		header_print(array(), '../', IELOG_REDIRECT_TIMEOUT);
		echo('登録されました．');
		footer_print();
		return;
	}
	$error = db_error();
	if (isset($error))
		error("$error");
} else if (($id = param_get_int('id')) !== 0) {
	$r = realestate_get($id);
	// use the same error message for all errors for security.
	if (! realestate_is_editable($r))
		error('編集する権限がありません．');
	$r['id'] = $id;
	$form->setDefaults($r);
}
header_print(array());
$form->display();
footer_print();
?>
