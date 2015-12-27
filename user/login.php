<?php
require_once('../lib.php');
require_once('../form.php');
require_once('lib.php');

$form = new Form('userLoginForm');
$form->addElement('header', null, 'ログイン');
$form->addElement('text', 'username', 'ユーザ名',
    array('size' => 50, 'maxlength' => 255));
$form->addElement('password', 'password', 'パスワード',
    array('size' => 50, 'maxlength' => 255));
$msg =& $form->addElement('static', null, '');
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
	$msg->setText(error_message('ユーザ名かパスワードが違います．'));
}

header_print('家ログ ログイン', array());
$form->display();
$uri = IELOG_URI;
echo <<<USERREGISTRATION
もし，まだアカウントを作成していない場合は[
<a href="$uri/user/register.php">ユーザ登録</a>
]から作成して下さい．
USERREGISTRATION;
footer_print();
?>
