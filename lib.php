<?php
/*
 * this requires a configuration file, config.php, like below.  note that
 * URI should not end with slash ('/') for now.
 *
 * <?php
 * define('IELOG_URI', 'http://ielog.hogehoge.org');
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

define('IELOG_ALLOWED_AGE',		20);

user_setup();

function
nav_link($links)
{

	if (empty($links))
		return '';
	$html = '<div id="nav">
        <ul>';
	foreach ($links as $name => $uri) {
		if ($uri !== null)
			$a = "href=\"$uri\"";
		else
			$a = 'href="#" class="require-login"';
		$html .= "
          <li><a $a><span>$name</span></a></li>";
	}
	$html .= '
        </ul>
      </div>';
	return $html;
}

function
header_print($title, $links, $redirecturi = NULL, $redirecttimeout = 0)
{
	static $uri = IELOG_URI;

	if ($redirecturi)
		$rmeta = <<<REDIRECTMETA

    <meta http-equiv="refresh" content="$redirecttimeout;URL=$redirecturi">
REDIRECTMETA;
	else
		$rmeta = '';
	$userlink = user_link();
	if (user_is_loggedin())
		$reguri = '/realestate/edit.php';
	else
		$reguri = null;
	$navlink = nav_link(array(
	    'Top' =>  $uri,
	    '検索' => $uri . '/search.php',
	    '一覧' => $uri . '/realestate/list.php',
	    '空家登録' => $reguri
	    ));
	$subnavlink = nav_link($links);
	if (! empty($subnavlink))
		$navlink .= "\n$subnavlink";

	echo <<<HEADER
<!DOCTYPE html>
<html lang="ja">
  <head>
    <link rel="index" href="./index.php" />
    <link rel="author" href="mailto:null@mobile-ip.org" />
    <link rel="stylesheet" type="text/css" href="$uri/css/style.css" />
    <link rel="stylesheet" type="text/css" href="$uri/scripts/jquery-ui/jquery-ui.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />$rmeta
    <title>$title</title>
  </head>
  <body>
    <div id="header">
      <div id="logo">
        <a href="$uri"><h1><img class="inline" alt="logo" src="$uri/images/logo.png" width="50" height="50">$title</h1></a>
      </div>
      <div id="user">
        $userlink
      </div>
    </div>
    $navlink
    <div id="main">

HEADER;
}

function
footer_print()
{
	$uri = IELOG_URI . '/scripts';

	echo <<<FOOTER
    </div>
    <div id="require-login-alert" class="hidden" title="アラート">
      ログインが必要です．
    </div>
    <script src="$uri/jquery/jquery.min.js"></script>
    <script src="$uri/jquery-ui/jquery-ui.min.js"></script>
    <script src="$uri/ielog.js"></script>
    <script type="text/javascript">
	$(function() {
		var dialog;
		dialog = $('#require-login-alert').dialog({
			autoOpen:	false,
			modal:		true,
			buttons: {
				'close': function () {
					$(this).dialog('close');
				}
			}
		});
		$('.require-login').on('click', function () {
			dialog.dialog('open');
		});
	});
    </script>
  </body>
</html>
FOOTER;
}

function
error_message($msg)
{

	return '<span class="error">' . $msg . '</span>';
}

function
error_print($msg)
{

	echo(error_message($msg));
}

function
param_get($name, $default = '')
{

	if (isset($_POST) && isset($_POST[$name]))
		return $_POST[$name];
	else if (isset($_GET) && isset($_GET[$name]))
		return $_GET[$name];
	else
		return $default;
}

function
param_get_int($name, $default = '')
{

	return (int)param_get($name, $default);
}

function
filename_clean($s)
{

	$s = preg_replace('/[[:cntrl:]"$&\'*\/:;<>?`\\\\|]/u', '', $s);
	if ($s === '.' || $s === '..')
		$s = '';
	return $s;
}

function
pathname_clean($s)
{

	if (empty($s))
		return '';
	$s = @iconv('UTF-8', 'UTF-8//IGNORE', $s);
	$s = str_replace('\\', '/', $s);
	$s = str_replace('//', '/', $s);
	$ss = explode('/', $s);
	$sa = array();
	foreach ($ss as $idx => $v) {
		if ($v === '.')
			continue;
		if ($v === '..') {
			if (array_pop($sa) === NULL)
				return '';
		} else if ($v = filename_clean($v))
			array_push($sa, $v);
	}
	return implode('/', $sa);
}

function
file_path($path)
{

	return IELOG_DATADIR . '/' . $path;
}

function
file_url($path)
{

	return IELOG_URI . '/file.php?path=' . $path;
}

function
image_url($path)
{

	return IELOG_URI . '/images/' . $path;
}

function
flip_link($table)
{
	$perpage = 10;
	$page = param_get_int('page', 1);

	$count = db_records_count($table);
	if ($page < 1 || $page > $count)
		$page = 1;
	else if (($page - 1) * $perpage > $count)
		--$page;
	$first = 1 + ($page - 1) * $perpage;
	$last  = $first + $perpage;
	if ($last > $count)
		$last = $count;

	$img = function($name) {
		$imguri = image_url("$name-arrow.png");
		return <<<IMG
<img class="inline" src="$imguri" alt="$name" width="32" height="32" />
IMG;
	};
	$leftarrow = $img('left');		/* XXX: link */
	$rightarrow = $img('right');		/* XXX: link */
	$link = "{$count}件中{$first}〜{$last}件表示";
	$link .= "{$leftarrow}..{$rightarrow}";	/* XXX: mediate links... */
	return $link;
}
?>
