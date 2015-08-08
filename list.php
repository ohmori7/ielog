<?php
require_once('lib.php');
header_print('家ログ 一覧', array());

for ($i = 1; $i < 11; $i++) {
	echo("<li><a href=\"detail.php&id=$i\">おばーちゃん画像$i 家の画像$i " .
	    "情報$i</a></li>\n");
}

footer_print();
?>
