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
$form->addElement('hidden', 'id', null);
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
$form->addElement('text', 'prefecture', '都道府県',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'city', '市町村',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'address', '住所',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('date', 'birthday', '誕生日',
    array('size' => 50, 'maxlength' => 255,
    'minYear' => 1900, 'maxYear' => date('Y') - IELOG_ALLOWED_AGE,
    'format' => 'Ymd', 'addEmptyOption' => true,
    'emptyOptionText' => array('Y' => 'YYYY', 'm' => 'mm', 'd' => 'dd')));
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
$form->addRule('firstname', '姓を入力して下さい．', 'required', null, 'client');

if ($form->isSubmitted() && $form->validate()) {
	$values = $form->exportValues();
	unset($values['passwordconfirm']);
	if ($new) {
		unset($values['id']);
		$id = user_add($values);
	} else {
		if (empty($values['password']) ||
		    empty($values['passwordconfirm']) /* XXX should be error */)
			unset($values['password']);
		$id = user_update($values);
	}
	if ($id === false)
		$error = db_error(); /* XXX */
	else if ($new) {
		header_print('家ログ', array(), 'login.php',
		    IELOG_REDIRECT_TIMEOUT);
		echo('登録されました．ログイン画面からログインして下さい．');
		footer_print();
		return;
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

header_print('家ログ', array());
if ($error)
	echo("ERROR: $error<br />");
$form->display();
footer_print();
?>
