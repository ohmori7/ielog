<?php
require_once('lib.php');
header_print('家ログ 検索', array());

$name = 'keyword';
$keyword = param_get($name);

?>
<form method="POST" action="list.php">
		<label for="address1">都道府県：</label><input type="text" name="address1" id="address1" value="" /><br/>
		<label for="address2">市町村：</label><input type="text" name="address1" id="address1" value="" /><br/>
		<label for="pay_type">種別：</label><select id="pay_type"><option>賃貸・売買</option><option>賃貸</option><option>売買</option></select><br/>
		<label for="age">築年数：</label><input type="text" name="age" id="age" value="" /><br/>
		<label for="keyword">キーワード：</label><input type="text" name="keyword" id="keyword" value="" /><br/>
	<input type="submit" value="送信" />
</form>
<?php
if (! empty($keyword))
	echo('○件がマッチしました．');

footer_print();
?>
