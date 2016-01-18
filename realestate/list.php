<?php
require_once('../lib.php');
require_once('../db.php');
require_once('lib.php');
header_print(array());

$fliplink = flip_link('realestate');
echo <<<HEADER
        <div id="menu" class="container ui-coner-all">
          <p class="container-header">検索条件 (作成中)</p>
          <ul>
            <li>市町村</li>
            <li>部屋数</li>
            <li>築年数</li>
            <li>敷地面積</li>
            <li>likeしたもの</li>
            <li>自身が所有者のもの</li>
          </ul>
          <button type="button">検索</button>
        </div>
        <div id="content">
          $fliplink
HEADER;

$rows = 0;
$rs = realestate_get();
foreach ($rs as $id => $r) {
	$estatepic = realestate_image_top_url($r);
	$ownerimg = realestate_image_owner_url($r);
	if (user_is_loggedin())
		$link = 'href="view.php?id=' . $id . '"';
	else
		$link = 'href="#" class="require-login"';
	$feedback = realestate_feedback_html($r);
	$contract = realestate_contract_name($r['contract']);
	$age = realestate_age($r);
	echo <<<RECORD
          <div class="container">
            <p class="container-header">物件 $id</p>
            <p style="float: left;">
              <img class="list-pic" alt="estate" src="{$estatepic}" />
              <img class="list-pic" alt="owner" src="{$ownerimg}" />
            </p>
            <p>
              住所: {$r['prefecture']}{$r['city']}{$r['address']}<br />
              契約形態: {$contract}，築年数: {$age}<br />
              概要:{$r['abstract']}<br />
            </p>
$feedback
            <p>
              <a $link>詳細</a>
            </p>
          </div>
RECORD;
}
echo <<<FOOTER
          $fliplink
        </div>
FOOTER;
footer_print();
?>
