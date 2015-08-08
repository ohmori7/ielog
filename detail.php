<?php
require_once('lib.php');
header_print('家ログ 詳細', array());

$id = '1'; /* XXX */

$owner = "images/owner${id}.png";
$appear = "images/appear${id}.png";

echo("<img alt=\"$owner\" src=\"$owner\" height=\"200px\" />\n");
echo("<img alt=\"$appear\" src=\"$appear\" height=\"200px\" />\n");

echo("<a href=\"contact.php?id=$id\">○○さんに問い合わせ</a>");

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
