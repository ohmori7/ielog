<?php
require_once('../lib.php');
require_once('../db.php');
require_once('lib.php');
header_print(array());

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
        <div id="content" class="container ui-corner-all">
          <p class="container-header">マップ(作成中)</p>
          <p>幼稚園: okm，保育園: okm，小学校: okm，中学校: okm，高校: okm</p>
          <p>市役所: okm</p>
          <p>最寄りバス停: okm</p>
          <p>最寄り駅: okm </p>
          <p>病院: okm</p>
          <p>スーパー: okm</p>
          <p>補助金とか…</p>
        </div>
HEADER;
footer_print();
?>
