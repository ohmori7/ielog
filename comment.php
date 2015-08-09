<?php
require_once('lib.php');
header_print('家ログ 評価', array());
?>
<p>
<form action="comment.php">
都市計画地域: <input name="plan" type="text" value="" /><br />
小学校校区: <input name="elementary" type="text" value="" /><br />
地価: <input name="price" type="text" value="" /><br />
防災情報: <input name="disaster" type="text" value="" /><br />
公共施設: <input name="facility" type="text" value="" /><br />
交通情報: <input name="traffic" type="text" value="" /><br />
メールアドレス: <input name="mailaddress" type="text" value="" /><br />
メッセージ: <textarea name="message" value="" width="30" height="20"></textarea><br />
<input type="button" value="送信" />
</form>
</p>
<?php
footer_print();
?>

