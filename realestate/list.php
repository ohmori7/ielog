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
          <table class="list">
            <thead>
              <tr>
                <th>No.</th>
                <th>詳細</th>
                <th>オーナー</th>
                <th>外観</th>
                <th>評価と概要</th>
                <th>契約形態</th>
                <th>築年数</th>
                <th>住所</th>
              </tr>
            </thead>
            <tbody>
HEADER;

$rows = 0;
$rs = realestate_get();
foreach ($rs as $id => $r) {
	$rowmod = $rows++ % 2;
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
              <tr class="list-row$rowmod">
                <td rowspan="2">$id</td>
                <td rowspan="2"><a $link>詳細</a></td>
                <td rowspan="2"><img class="list-pic" alt="owner" src="{$ownerimg}" /></td>
                <td rowspan="2"><img class="list-pic" alt="estate" src="{$estatepic}" /></td>
                <td class="list-rate">
$feedback
                </td>
                <td rowspan="2">{$contract}</td>
                <td rowspan="2">{$age}</td>
                <td rowspan="2">{$r['prefecture']}{$r['city']}{$r['address']}</td>
              </tr>

              <tr class="list-row$rowmod">
                <td class="list-abstract">{$r['abstract']}</td>
              </tr>

RECORD;
}
echo <<<FOOTER
            </tbody>
          </table>
          $fliplink
        </div>
FOOTER;
footer_print();
?>
