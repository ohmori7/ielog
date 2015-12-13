<?php
require_once('../lib.php');
require_once('lib.php');

require_once 'HTML/QuickForm.php';

user_login();

$form = new HTML_QuickForm('userRegistrationForm');
$form->addElement('header', null, '物件登録');
$form->addElement('textarea', 'abstract', '概要',
    array('size' => 100, 'maxlength' => 255));
$form->addElement('textarea', 'description', '詳細な説明',
    array('size' => 100, 'maxlength' => 255));
$payments = array(
    REALESTATE_PAYMENT_TYPE_RENTAL =>		'賃貸',
    REALESTATE_PAYMENT_TYPE_SALE =>		'売買',
    REALESTATE_PAYMENT_TYPE_RENTAL_AND_SALE =>	'賃貸・売買'
    );
$form->addElement('select', 'payment', '賃貸種別', $payments);
$form->addElement('text', 'builtdate', '建築日',
    array('size' => 50, 'maxlength' => 50));
$file =& $form->addElement('file', 'file', '外観の画像ファイル');
$form->addElement('text', 'prefecture', '都道府県',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'city', '市町村',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'address', '住所',
    array('size' => 100, 'maxlength' => 255));
$form->addElement('submit', null, '登録');

$form->addRule('abstract', '概要を入力して下さい．',
    'required', null, 'client');
$form->addRule('description', '詳細な説明を入力して下さい．',
    'required', null, 'client');
$form->addRule('payment', '賃貸種別を入力して下さい．',
    'required', null, 'client');
$form->addRule('builtdate', '建築日を入力して下さい．',
    'required', null, 'client');
$form->addRule('file', '外観の画像ファイルを入力して下さい．',
    'required', null, 'client');

if ($form->isSubmitted() && $form->validate()) {
	if (! $file->isUploadedFile()) {
		echo('ERROR: inconsitent state!!');
		die();
	}
	$values = $form->exportValues();
	$filename = $file->_value['name']; /* XXX */
	if (preg_match('/^.*(\.[^[\.]+)$/', $filename, $matches))
		$ext = $matches[1];
	else
		$ext = '';
	$filename = "pic$ext";
	$values['picture'] = $filename;
	$id = realestate_add($values);
	if ($id !== false) {
		$dir = realestate_data_dir($id);
		mkdir($dir, 0700, true);
		$file->moveUploadedFile($dir, $filename);

		header_print('家ログ 空家の追加', array(), '../',
		    IELOG_REDIRECT_TIMEOUT);
		echo('登録されました．');
		footer_print();
		return;
	}
	$error = db_error();
}
header_print('家ログ 空家の追加', array());
if ($error)
	echo("ERROR: $error<br />");
$form->display();
footer_print();
?>
