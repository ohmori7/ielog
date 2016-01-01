<?php
require_once('lib.php');

$path = pathname_clean(param_get('path'));
if (empty($path))
	die('invalid argument');
$path = IELOG_DATADIR . '/' . $path;

$forcetodownload = false;	/* XXX: not yet */

$ifmodifiedsince = filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE');
$ifnonematch = filter_input(INPUT_SERVER, 'HTTP_IF_NONE_MATCH');
$mtime = gmdate('D, d M Y H:i:s T', filemtime($path));
if ($ifmodifiedsince === $mtime ||
    ($ifnonematch !== NULL &&
     ($etag = hash_file('sha256', $path)) === $ifnonematch)) {
	/*
	 * avoid apache autonomously generates cache-control header,
	 * and utilize this nature to return 304 Not-Modified.
	 */
	header('Cache-Control: no-cache', true, 304);
	exit;
}
if (! isset($etag))
	$etag = hash_file('sha256', $path);

$stat = stat($path);
if ($stat === false)
	die('No such file or directory');
$size = $stat['size'];

$finfo = new finfo(FILEINFO_MIME);
$mime = $finfo->file($path);
if ($mime === false)
	$mime = 'application/octet-stream';

header('Cache-Control: no-cache', true);
header('Last-Modified: ' . $mtime, true);
header('ETag: '. '"' . $etag . '"', true);
header('Content-Type: ' . $mime);
header('Content-Length: ' . $size);
if ($forcetodownload) {
	$filename = basename($path);
	/* XXX: in case of IE, should urlencode()...*/
	header("Content-disposition: attachment; filename=$filename");
}
readfile($path);
?>
