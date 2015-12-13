<?php
require_once('../lib.php');
require_once('lib.php');

user_logout();
header_print('家ログ', array(), '../index.php');
footer_print();
?>
