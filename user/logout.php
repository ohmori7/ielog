<?php
require_once('../lib.php');
require_once('lib.php');

user_logout();
header_print(array(), '../index.php');
footer_print();
?>
