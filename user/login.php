<?php
require_once('../lib.php');
require_once('lib.php');

require_once 'HTML/QuickForm.php';

$form = new HTML_QuickForm('userLoginForm');
$form->addElement('header', null, 'ログイン');
$form->addElement('text', 'username', 'ユーザ名',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('password', 'password', 'パスワード',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('submit', null, 'ログイン');

$form->applyFilter('username', 'trim');
$form->addRule('username', 'ユーザ名を入力して下さい．',
    'required', null, 'client');
$form->addRule('password', 'パスワードを入力して下さい．',
    'required', null, 'client');

if ($form->isSubmitted() && $form->validate()) {
	$values = $form->exportValues();
	$username = $values['username'];
	$password = $values['password'];
	if (user_authenticate($username, $password) === true) {
		header_print('家ログ ログイン', array(), '../index.php');
		echo('ログインしました．遷移しない場合はURL(<a href="../index.php">index.php</a>)をクリックして下さい．');
		footer_print();
		return;
	}
}

header_print('家ログ ログイン', array());
$form->display();
footer_print();
?>
