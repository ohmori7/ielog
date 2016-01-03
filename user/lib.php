<?php
function
user_mail_address_check($maddr)
{
	if (! preg_match('/^[a-zA-Z0-9\._-]+\@([a-zA-Z0-9\-\.]+[^\.]+)$/',
	    $maddr, $matches))
		return false;
	$domain = $matches[1];
	if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A'))
		return true;
	return false;
}

function
user_password_hash($password)
{

	if (function_exists('password_hash'))
		return password_hash($password, PASSWORD_BCRYPT);

	// old PHP does not have password_hash()...
	// XXX: salt...
	return crypt($password);
}

function
user_password_verify($password, $hash)
{

	if (function_exists('password_verify'))
		return password_verify($password, $hash);

	// old PHP does not have password_verify() as well...
	if (! preg_match('/^(.*\$)([^\$]+)$/', $hash, $matches))
		return false;
	$salt = $matches[1];
	return crypt($password, $salt) === $hash;
}

function
user_variable_update($user)
{
	global $USER;

	foreach ($user as $key => $value)
		if ($key !== 'password')
			$USER->$key = $value;
}

function
user_add($user)
{

	$user['password'] = user_password_hash($user['password']);
	return db_record_insert('user', $user);
}

function
user_update($user)
{

	if (! empty($user['password']))
		$user['password'] = user_password_hash($user['password']);
	$rc = db_record_update('user', $user);
	user_variable_update($user);
	return $rc;
}

function
user_del()
{
}

function
user_get($id)
{

	return db_record_get('user', array('id' => $id));
}

function
user_get_by_mail($mail)
{

	return db_record_get('user', array('mail' => $mail));
}

function
user_is_loggedin()
{
	global $_SESSION;

	return isset($_SESSION['user']);
}

function
user_login()
{

	if (user_is_loggedin())
		return;
	if (defined('IELOG_JSON'))
		json_respond(false, 'loginexpire');
	header_print(array(), IELOG_URI . '/user/login.php',
	    IELOG_REDIRECT_TIMEOUT);
	echo('ログインが必要です．');
	footer_print();
	exit(1);
}

function
user_setup()
{
	global $_SESSION, $USER;

	if ($_SESSION)
		return;

	define('IELOG_SESSION_NAME',		'IELOG_SESSION');
	define('IELOG_SESSION_TIMEOUT', 	2 * 60 * 60);

	session_name(IELOG_SESSION_NAME);
	/* see http://php.net/manual/ja/session.configuration.php. */
	$timeout = IELOG_SESSION_TIMEOUT;
	ini_set('session.cookie_lifetime',	$timeout);
	ini_set('session.gc_maxlifetime',	$timeout);
	ini_set('session.gc_probability',	1);
	ini_set('session.gc_divisor',		1);
	session_start();
	if (isset($_SESSION['user']))
		$USER = $_SESSION['user'];
	/* update cookie on each access. */
	setcookie(session_name(), session_id(), time() + $timeout, '/');
}

function
user_logout($user)
{

	if (! isset($_COOKIE[session_name()]))
		return;
	setcookie(session_name(), '', time() - 3600);
	session_destroy();
	$_SESSION = array();
	session_unset();
}

function
user_authenticate($mail, $password)
{
	global $USER, $_SESSION;

	$user = user_get_by_mail($mail);
	if ($user === false)
		return false;
	if (! user_password_verify($password, $user['password']))
		return false;
	$USER = new stdClass();
	$_SESSION['user'] =& $USER;
	user_variable_update($user);
	return true;
}

function
user_redirect_after_loggedin($uri = IELOG_URI)
{

	header_print(array(), $uri);
	echo <<<MSG
ログインしました．
遷移しない場合はURL(<a href="$uri">$uri</a>)をクリックして下さい．
MSG;
	footer_print();
	exit(1);
}

function
user_name()
{
	global $USER;

	if (! user_is_loggedin())
		return '';
	return implode(' ', array($USER->lastname, $USER->firstname));
}

function
user_link()
{

	if (user_is_loggedin()) {
		$editurl = IELOG_URI . '/user/edit.php';
		$logouturl = IELOG_URI . '/user/logout.php';
		$username = user_name();
		$html = <<<LOGOUT
<a href="$editurl">$username</a>（<a href="$logouturl">ログアウト</a>）
LOGOUT;
	} else {
		$loginurl = IELOG_URI . '/user/login.php';
		$html = 'ログインしていません';
		$html .= '（<a href="' . $loginurl . '">ログイン</a>）';
	}
	return $html;
}

function
user_data_dirbase($id)
{

	return 'user/' . $id . '/';
}

function
user_data_dir($id)
{

	return file_path(user_data_dirbase($id));
}

function
user_data_url($id, $filename)
{

	return file_url(user_data_dirbase($id) . $filename);
}

function
user_picture_url($user)
{

	if (! is_array($user))
		$user = user_get($user);
	$filename = $user['picture'];
	if (file_exists(user_data_dir($user['id']) . $filename))
		return user_data_url($user['id'], $filename);
	return image_url('noimage.png');
}
?>
