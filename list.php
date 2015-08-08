<!DOCTYPE html>
<html lang="ja">
<head>
<title>家ログ</title>
</head>
<body>
<h1><img alt="logo" src="logo.png" />家ログ 一覧</h1>
<ol>
<?php
for ($i = 0; $i < 10; $i++) {
	echo("<li><a href=\"detail.php&id=$i\"おばーちゃん画像$i 家の画像$i " .
	    "情報$i</a></li>\n");
}
?>
</body>
</html>
