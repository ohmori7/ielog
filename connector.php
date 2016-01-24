<?php

require_once('lib.php');
require_once('json.php');
require_once('realestate/lib.php');

user_require_login();
$id = param_get_int('id');
if (! realestate_is_editable(realestate_get($id)))
	json_respond('', 'errPerm');
$path = realestate_data_dir($id);
$url = realestate_data_url($id);
$efpath = 'scripts/elfinder/php';

include_once("$efpath/elFinderConnector.class.php");
include_once("$efpath/elFinder.class.php");
include_once("$efpath/elFinderVolumeDriver.class.php");
include_once("$efpath/elFinderVolumeLocalFileSystem.class.php");

function
access($attr, $path, $data, $volume)
{
	return strpos(basename($path), '.') === 0
		? !($attr == 'read' || $attr == 'write')
		:  null;
}

$opts = array(
	'roots' => array(
		array(
			'driver'	=> 'LocalFileSystem',
			'path'		=> $path,
			'URL'		=> "$url",
			'acceptedName'	=> '/^[^\.].*$/',
                        'disabled'	=> array('netmount', 'forward', 'back', 'mkfile', 'mkdir', 'extract', 'archive', 'copy', 'cut', 'paste', 'edit', 'duplicate', 'help'),
			'uploadDeny'	=> array('all'),
			'uploadAllow'	=> array('image'),
			'uploadOrder'	=> array('deny', 'allow'),
			'accessControl'	=> 'access'
		)
	)
);

$c = new elFinderConnector(new elFinder($opts));
$c->run();
?>
