<?php
require_once('../lib.php');
require_once('lib.php');

user_login();

header_print('家ログ 詳細', array());

$id = param_get('id');
$r = db_record_get('realestate', 'id', $id);
if ($r === false) {
	echo('ERROR: wrong page transition!!');
	footer_print();
	die();
}
$appear = realestate_image_top_url($r);
$owner = realestate_image_owner_url($r);
echo <<<TOP
    <div id="tabmenu">
      <div id="tab">
        <a href="#tab1">詳細情報</a>
        <a href="#tab2">写真</a>
        <a href="#tab3">口コミ</a>
      </div>
      <div id="tab_contents">
        <ul>
          <!-- 詳細情報 -->
          <li id="tab1" name="tab1">
            <h2>詳細情報</h2>
            <div class="detail_photo">
              <h3>概観</h3>
              <img alt="$appear" src="$appear" width="250px" />
              <h3>オーナー</h3>
              <img alt="$owner" src="$owner" width="250px" />
            </div>
            <div class="detail_msg">
              <h3>評価</h3>
TOP;
realestate_radar_graph_puts($r);
echo <<<MIDDLE
              <h3>概要</h3>
              {$r['abstract']}
              <h3>説明</h3>
              {$r['description']}
            </div>
            <div style="clear:both;">
              <h3>みんなの評価</h3>
              <div id="realestate1-score" class="score">
                <img alt="score" src="../images/star3.png" />
              </div>
              <div id="realestate1-like" class="like">
                <img id="realestate1-likeimg" src="../images/like.png" width="24px" height="24px" />
                <span>いいね！</span>
                <span id="realestate1-like-count">0</span>
              </div>
              <div id="realestate1-favorite" class="favorite">
                <img id="realestate1-favoriteimg" src="../images/favorite.png" width="24px" height="24px" />
                <span>お気に入り</span>
                <span id="realestate1-favorite-count">0</span>
              </div>
            </div>
          </li>
          <!-- 写真 -->
          <li id="tab2" name="tab2">
            <h2>写真</h2>
            <img alt="$owner" src="$owner"  width="250px" style="margin: 5px;" />
            <img alt="$appear" src="$appear" width="250px" style="margin: 5px;" />
            <img alt="$appear" src="$appear" width="250px" style="margin: 5px;" />
            <h2>室内・周辺</h2>
            <blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影2 #theta360 - <a href="https://theta360.com/s/q41fN1dypHKIyAdQUdJz4AeHs" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
            <script async src="https://theta360.com/widgets.js" charset="utf-8"></script><br/>
            <blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影1 #theta360 - <a href="https://theta360.com/s/lD8zKgT91lqWW2gwzvVt5qN4W" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
            <script async src="https://theta360.com/widgets.js" charset="utf-8"></script>
          </li>
          <!-- 口コミ -->
          <li id="tab3" name="tab3">
            <h2>口コミ</h2>
            <div class="balloon-wrapper">
MIDDLE;
$ncomments = 6; /* XXX */
for ($i = 0; $i < $ncomments; $i++) {
	$msg = "また、資源をエネルギーに変換するためには、要らないものを空気中に排出しなくてはなりません。二酸化炭素などの排出物は、大気汚染を引き起こし、やがては地球全体の気温を徐々にあげてしまいます。これが地球温暖化と呼ばれるもので、異常気象や農作物への影響、海水面の上昇、紫外線の問題など、さまざまな問題の原因となっています。人間だけでなく、すべての生き物の住みやすい環境を守るためにも、エネルギーの節約は必要なのです。 <br />";
	$msg .= "<img alt=\"score\" src=\"../images/star${id}.png\" />"; // XXX
	if($i % 2 == 0) {
		echo("<img src=\"../images/icon1.png\" class=\"balloon-left-img\" />\n");
		echo("<p class=\"balloon-left\">$msg</p>\n");
	} else {
		echo("<p class=\"balloon-right\">$msg</p>\n");
		echo("<img src=\"../images/icon1.png\" class=\"balloon-right-img\" />\n");
	}
	echo("<p class=\"clear-p\">&nbsp;</p>\n");
}
echo <<<BOTTOM
            </div>
          </li>
        </ul>
      </div>
    </div>
    <a href="../contact.php?id=$id">○○さんに問い合わせ</a>
BOTTOM;
footer_print();
?>
