<?php
require_once('../lib.php');
require_once('../db.php');
require_once('lib.php');
header_print('家ログ', array());

echo <<<HEADER
      <h2>物件一覧</h2>
      <table class="list">
        <thead>
          <tr>
            <th>No.</th>
            <th>詳細</th>
            <th>オーナー</th>
            <th>外観</th>
            <th>評価と概要</th>
          </tr>
        </thead>
        <tbody>
HEADER;
$rate = 0; /* XXX */
$rateimg = "../images/star$rate.png"; /* XXX */
$rows = 0;

$rs = db_records_get('realestate');
foreach ($rs as $id => $r) {
	$rowmod = $rows++ % 2;
	$estatepic = realestate_image_top_url($r);
	$ownerimg = realestate_image_owner_url($r);
	echo <<<RECORD
          <tr class="list-row$rowmod">
            <td rowspan="2">$id</td>
            <td rowspan="2"><a href="detail.php?id=$id">詳細</a></td>
            <td rowspan="2"><img class="list-pic" src="{$ownerimg}" /></td>
            <td rowspan="2"><img class="list-pic" src="{$estatepic}" /></td>
            <td class="list-rate"><img src="{$rateimg}" /></td>
          </tr>
          <tr class="list-row$rowmod">
            <td class="list-abstract">{$r['abstract']}</td>
          </tr>

RECORD;
}
echo <<<FOOTER
        </tbody>
      </table>
FOOTER;
footer_print();
?>
