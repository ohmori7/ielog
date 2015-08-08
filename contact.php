<?php
require_once('lib.php');
header_print('家ログ 問い合わせ', array());
?>
<h1><img alt="logo" src="logo.png" />
<p>
<form action="contact.php">
お名前: <input type="text" value="" />
メールアドレス: <input type="text" value="" />
メッセージ: <textarea value="" width="30" height="20" />
<input type="button" value="送信" />
</form>
<?php
footer_print();
?>
