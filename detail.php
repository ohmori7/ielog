<?php
require_once('lib.php');
header_print('家ログ 詳細', array());

echo('<a href="contact.php?id=XXXX">contact</a>');

$ncomments = 10; /* XXX */
for ($i = 0; $i < $ncomments; $i++) {
	echo("<div class=\"container\" >\n");
	echo("<div class=\"commentator\">\n");
	echo("<img alt=\"fugafuga\" src=\"hogehoge\" />\n");
	echo("</div>\n");
	echo("<div class=\"comment\">");
	echo('hogehoge');
	echo("</div>\n");
	echo("</div>\n");
}

footer_print();
?>
