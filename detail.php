<?php
require_once('lib.php');
header_print('家ログ 詳細', array());

$id = '1'; /* XXX */
echo("<a href=\"contact.php?id=$id\">contact</a>");

$ncomments = 10; /* XXX */
for ($i = 0; $i < $ncomments; $i++) {
	$imgfile = "images/star${id}.png";	// XXX
	echo("<div class=\"container\">\n");
	echo("<div class=\"commentator\">\n");
	echo("<img alt=\"fugafuga\" src=\"hogehoge\" />");
	echo("</div>\n");
	echo("<div class=\"comment\">");
	echo('hogehoge');
	echo("<img alt=\"score\" src=\"$imgfile\" />");
	echo("</div>\n");
	echo("</div>\n");
}

footer_print();
?>
