<?php
require_once('../lib.php');
require_once('../form.php');
require_once('lib.php');

if (! user_is_loggedin())
	$new = true;
else
	$new = false;

$form = new Form('userRegistrationForm');
$form->addElement('header', null, 'ユーザ登録');
$form->addElement('text', 'mail', 'メールアドレス',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('password', 'password', 'パスワード',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('password', 'passwordconfirm', 'パスワード（確認）',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'lastname', '姓',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'firstname', '名',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('date', 'birthday', '誕生日',
    array('size' => 50, 'maxlength' => 255,
    'minYear' => 1900, 'maxYear' => date('Y') - IELOG_ALLOWED_AGE,
    'format' => 'Ymd', 'addEmptyOption' => true,
    'emptyOptionText' => array('Y' => 'YYYY', 'm' => 'mm', 'd' => 'dd')));
$pic =& $form->addElement('file', 'picture', '顔写真');
$form->addElement('text', 'zip', '郵便番号',
    array('size' => 16, 'maxlength' => 16));
$form->addElement('text', 'prefecture', '都道府県',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'city', '市町村',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'address', '住所',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('submit', null, '登録');

$form->applyFilter('mail', 'trim');
$form->addRule('mail', 'メールアドレスを入力して下さい．',
    'required', null, 'client');
$form->addRule('mail', 'メールアドレスが正しくありません．',
    'email', null, 'client');
$form->registerRule('checkmaildomain', 'callback', 'user_mail_address_check');
$form->addRule('mail', 'メールアドレスが存在しません．',
    'checkmaildomain', true);
if ($new) {
	$form->addRule('password', 'パスワードを入力して下さい．',
	    'required', null, 'client');
	$form->addRule('password',
	    'パスワードは12文字以上でなければなりません．',
	    'rangelength', array(12, 255), 'client');
	$form->addRule('passwordconfirm',
	    'パスワード（確認）を入力して下さい．',
	    'required', null, 'client');
}
$form->addRule(array('password', 'passwordconfirm'),
    'パスワードが一致していません．', 'compare', 'eq', 'client');
$form->addRule('lastname', '姓を入力して下さい．', 'required', null, 'client');
$form->addRule('firstname', '名を入力して下さい．', 'required', null, 'client');
$form->addRule('birthday', '誕生日を入力して下さい．',
    'required', null, 'client');
$form->addRule('zip', '数字を入力して下さい．', 'numeric', null, 'client');

if ($form->isSubmitted() && $form->validate()) {
	$values = $form->exportValues();
	unset($values['id']);	/* just in case */
	unset($values['passwordconfirm']);
	unset($values['MAX_FILE_SIZE']);
	if ($pic->isUploadedFile()) {
		$filename = $pic->_value['name']; /* XXX */
		if (preg_match('/^.*(\.[^[\.]+)$/', $filename, $matches))
			$ext = $matches[1];
		else
			$ext = '';
		$filename = "pic$ext"; /* XXX */
		$values['picture'] = $filename;
	}
	if ($new)
		$id = user_add($values);
	else {
		$values['id'] = $USER->id;
		if (empty($values['password']))
			unset($values['password']);
		$id = user_update($values);
	}
	if ($id === false)
		$error = db_error(); /* XXX */
	else {
		if ($pic->isUploadedFile()) {
			$dir = user_data_dir($id);
			mkdir($dir, 0700, true);
			$pic->moveUploadedFile($dir, $filename);
		}
		if ($new) {
			header_print(array(), 'login.php',
			    IELOG_REDIRECT_TIMEOUT);
			echo('登録されました．');
			echo('ログイン画面からログインして下さい．');
			footer_print();
			return;
		}
        }
} else if (! $new) {
	$values = array();
	foreach ((array)$USER as $key => $value) {
		if ($key === 'password')
			continue;
		if (empty($value))
			continue;
		$values[$key] = $value;
	}
	$form->setDefaults($values);
}

header_print(array());
if ($error)
	echo("ERROR: $error<br />");
$form->display();
footer_print();
?>
