<?php
require_once('../lib.php');
require_once('../form.php');
require_once('lib.php');

if (user_is_loggedin()) {
	header_print('家ログ', array(), '../index.php',
	    IELOG_REDIRECT_TIMEOUT);
	echo('既にログイン済みの状態では登録できません．');
	footer_print();
	exit(1);
}

$form = new Form('userRegistrationForm');
$form->addElement('header', null, 'ユーザ登録');
$form->addElement('text', 'username', 'ユーザ名',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('password', 'password', 'パスワード',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('password', 'passwordconfirm', 'パスワード（確認）',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('text', 'mail', 'メールアドレス',
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
$form->addElement('text', 'birthday', '誕生日',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('submit', null, '登録');

$form->applyFilter('username', 'trim');
$form->addRule('username', 'ユーザ名を入力して下さい．',
    'required', null, 'client');
$form->addRule('username', 'ユーザ名はアルファベットと数字しか使えません．',
    'alphanumeric', null, 'client');
$form->addRule('password', 'パスワードを入力して下さい．',
    'required', null, 'client');
$form->addRule('password', 'パスワードは12文字以上でなければなりません．',
    'rangelength', array(12, 255), 'client');
$form->addRule('passwordconfirm', 'パスワード（確認）を入力して下さい．',
    'required', null, 'client');
$form->addRule(array('password', 'passwordconfirm'),
    'パスワードが一致していません．', 'compare', 'eq', 'client');
$form->addRule('mail', 'メールアドレスを入力して下さい．',
    'required', null, 'client');
$form->addRule('mail', 'メールアドレスが正しくありません．',
    'email', null, 'client');
$form->registerRule('checkmaildomain', 'callback', 'user_mail_address_check');
$form->addRule('mail', 'メールアドレスが存在しません．',
    'checkmaildomain', true);
$form->addRule('lastname', '姓を入力して下さい．', 'required', null, 'client');
$form->addRule('firstname', '姓を入力して下さい．', 'required', null, 'client');

if ($form->isSubmitted() && $form->validate()) {
	$values = $form->exportValues();
	unset($values['passwordconfirm']);
	$id = user_add($values);
	if ($id !== false) {
		header_print('家ログ', array(), 'login.php',
		    IELOG_REDIRECT_TIMEOUT);
		echo('登録されました．ログイン画面からして下さい．');
		footer_print();
		return;
	}
	$error = db_error(); /* XXX */
}
header_print('家ログ', array());
if ($error)
	echo("ERROR: $error<br />");
$form->display();
footer_print();
?>
