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

$_ielog_header_is_printed_out = false;
$_ielog_footer_is_printed_out = false;
$_ielog_csses = '';
$_ielog_scripts = '';

css_file_add('css/style.css');
css_file_add('scripts/jquery-ui/jquery-ui.css');

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
header_print_check($errormsg)
{
	global $_ielog_header_is_printed_out;

	if ($_ielog_header_is_printed_out)
		throw new Exception($errormsg);
}

function
footer_print_check($errormsg)
{
	global $_ielog_footer_is_printed_out;

	if ($_ielog_footer_is_printed_out)
		throw new Exception($errormsg);
}

function
header_print($links, $redirecturi = NULL, $redirecttimeout = 0)
{
	global $_ielog_csses;
	static $uri = IELOG_URI;
	static $title = '家ログ';

	header_print_check('header has been already printed out');
	$_ielog_header_is_printed_out = true;

	if ($redirecturi)
		$rmeta = <<<REDIRECTMETA

    <meta http-equiv="refresh" content="$redirecttimeout; URL=$redirecturi">
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
    <link rel="author" href="mailto:null@mobile-ip.org" />$_ielog_csses
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />$rmeta
    <title>$title</title>
  </head>
  <body>
    <div id="header" class="clearfix">
      <div id="logo">
        <a href="$uri"><h1><img class="inline" alt="logo" src="$uri/images/logo.png" width="50" height="50">$title</h1></a>
      </div>
      <div id="user">
        $userlink
      </div>
      $navlink
    </div>
    <div id="main">

HEADER;
}

function
footer_print()
{
	global $_ielog_scripts;
	$uri = IELOG_URI . '/scripts';

	footer_print_check('footer has been already printed out');
	$_ielog_footer_is_printed_out = true;
	echo <<<FOOTER
    </div>
    <div id="alert-dialog" class="ui-widget hidden" title="アラート">
      <div id="alert-dialog-container" class="ui-corner-all">
        <p>
          <span id="alert-dialog-icon" class="ui-icon"></span>
          <span id="alert-dialog-message"></span>
        </p>
      </div>
    </div>
    <script src="$uri/jquery/jquery.min.js"></script>
    <script src="$uri/jquery-ui/jquery-ui.min.js"></script>
    <script src="$uri/ielog.js"></script>$_ielog_scripts
  </body>
</html>
FOOTER;
}

function
css_file_add($path)
{
	global $_ielog_csses;
	static $uri = IELOG_URI;

	header_print_check('CSS addition after a header is printed out');
	$_ielog_csses .= <<<LINK

    <link rel="stylesheet" type="text/css" media="screen" charset="utf-8" href="$uri/$path" />
LINK;
}

function
script_add($lines)
{
	global $_ielog_scripts;

	footer_print_check('scripts addition after a header is printed out');
	$_ielog_scripts .= $lines;
}

function
script_code_add($code)
{

	script_add("
    <script type=\"text/javascript\" charset=\"utf-8\">$code
    </script>");
}

function
script_file_add($path)
{
	$url = IELOG_URI . '/' . $path;
	script_add("
    <script type=\"text/javascript\" charset=\"utf-8\" src=\"$url\"></script>");
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
param_get_html($name, $default = '')
{
	require_once('HTMLPurifier.auto.php');

	$data = param_get($name, $default);
	$config = HTMLPurifier_Config::createDefault();
	$config->set('Core.Encoding', 'UTF-8');
	/*
	 * XXX: Purifier does not support HTML5...
	 *
	 * $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	 */
	$purifier = new HTMLPurifier($config);
	return $purifier->purify($data);
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
