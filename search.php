<?php
require_once('lib.php');
header_print('家ログ 検索', array());

$name = 'keyword';
$keyword = param_get($name);

echo("
<form method=\"POST\" action=\"search.php\">
キーワード: <input type=\"text\" name=\"$name\" value=\"$keyword\" />
<input type=\"submit\" value=\"送信\" />
</form>
	");

if (! empty($keyword))
	echo('○件がマッチしました．');

footer_print();
?>
