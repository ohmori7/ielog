<?php
require_once('lib.php');

$path = pathname_clean(param_get('path'));
if (empty($path))
	die('invalid argument');
$path = IELOG_DATADIR . '/' . $path;

$stat = stat($path);
if ($stat === false)
	die('No such file or directory');
$size = $stat['size'];

$finfo = new finfo(FILEINFO_MIME);
$mime = $finfo->file($path);
if ($mime === false)
	$mime = 'application/octet-stream';

header('Content-Type: ' . $mime);
if ($forcetodownload /* XXX: not yet */) {
	$filename = basename($path);
	/* XXX: in case of IE, should urlencode()...*/
	header("Content-disposition: attachment; filename=$filename");
	header("Content-Length: $size");
}
readfile($path);
?>
