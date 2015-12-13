<?php
/*
 * this requires a configuration file, config.php, like below.
 * <?php
 * define('IELOG_URI', 'http://ielog.hogehoge.org/');
 * $dbserver	= 'localhost';
 * $dbuser	= 'username';
 * $dbpasswd	= 'password';
 * $dbname	= 'ielog';
 * ?>
 */
require_once('config.php');
require_once('db.php');
require_once('user/lib.php');

define('IELOG_REDIRECT_TIMEOUT',	5);

user_setup();

function
nav_print($links)
{

	if (empty($links))
		return;
	echo '
      <div id="nav">
        <ul>';
	foreach ($links as $name => $uri)
		echo "
          <li><a href=\"$uri\"><span>$name</span></a></li>";
	echo '
        </ul>
      </div>';
}

function
header_print($title, $links, $redirecturi = NULL, $redirecttimeout = 0)
{
	static $uri = IELOG_URI;

	if ($redirecturi)
		$redirectmeta =
'    <meta http-equiv="refresh" content="' . $redirecttimeout . ';URL=' . $redirecturi . '">
';
	else
		$redirectmeta = '';

	echo
'<!DOCTYPE html>
<html lang="ja">
  <head>
    <link rel="index" href="./index.php" />
    <link rev="made" href="mailto:null@mobile-ip.org" />
    <link href="' . $uri . 'css/style.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' .
    $redirectmeta . 
    '<title>' . $title . '</title>
  </head>
  <body>
    <h1><img class="inline" alt="logo" src="' . $uri .
        'images/logo.png" width="50" height="50">' . $title . '</h1>';
	user_link_puts();
	nav_print(array('Top' =>  $uri,
	    '検索' => $uri . 'search.php',
	    '一覧' => $uri . 'list.php',
	    'ユーザ登録' =>  $uri . 'user/register.php',
	    ));
	nav_print($links);
	echo '
    <div id="main">';
}

function
footer_print()
{

	echo
'    </div>
  </body>
</html>';
}

// old PHP does not have password_hash()...
function
password_hash($password)
{

	// XXX: salt...
	return crypt($password);
}

function
password_verify($password, $hash)
{

	if (! preg_match('/^(.*\$)([^\$]+)$/', $hash, $matches))
		return false;
	$salt = $matches[1];
	return crypt($password, $salt) === $hash;
}

function
param_get($name)
{

	if (isset($_POST) && isset($_POST[$name]))
		return $_POST[$name];
	else if (isset($_GET) && isset($_GET[$name]))
		return $_GET[$name];
	else
		return '';
}
?>
