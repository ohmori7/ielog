<?php
require_once('lib.php');
header_print('家ログ 問い合わせ', array());
?>
<p>
<form action="contact.php">
お名前: <input type="text" value="" /><br />
メールアドレス: <input type="text" value="" /><br />
メッセージ: <textarea value="" width="30" height="20"></textarea><br />
<input type="button" value="送信" />
</form>
</p>
<?php
footer_print();
?>
