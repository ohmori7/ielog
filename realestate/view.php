<?php
require_once('../lib.php');
require_once('lib.php');

user_login();

header_print('家ログ 詳細', array());

$id = param_get_int('id');
$r = realestate_get($id);
if ($r === false) {
	echo('ERROR: wrong page transition!!');
	footer_print();
	die();
}
$address = $r['prefecture'] . $r['city'] . $r['address'];
$contract = realestate_contract_name($r['contract']);
$appear = realestate_image_top_url($r);
$owner = realestate_image_owner_url($r);
$age = realestate_age($r);
$like = realestate_like_html($r, true);
echo <<<TOP
        <!-- 詳細情報 -->
        <h2>詳細情報</h2>
        <div class="clearfix">
          <div class="detail_photo">
            <h3>概観</h3>
            <img alt="$appear" src="$appear" width="250" />
            <h3>オーナー</h3>
            <img alt="$owner" src="$owner" width="250" />
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
        </div>
        <div class="clearfix">
          <h3>住所</h3>
          $address
          <h3>契約形態</h3>
          $contract
          <h3>築年数</h3>
          $age
          <h3>みんなの評価</h3>
          <div class="clearfix">
            <div id="realestate$id-score" class="score">
              <img alt="score" src="../images/star3.png" />
            </div>
            <div>
$like
            </div>
          </div>
          <h2>写真</h2>
          <img alt="$owner" src="$owner"  width="250" style="margin: 5;" />
          <img alt="$appear" src="$appear" width="250" style="margin: 5;" />
          <img alt="$appear" src="$appear" width="250" style="margin: 5;" />
          <h2>室内・周辺</h2>
          <blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影2 #theta360 - <a href="https://theta360.com/s/q41fN1dypHKIyAdQUdJz4AeHs" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
          <script async src="https://theta360.com/widgets.js" charset="utf-8"></script><br/>
          <blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影1 #theta360 - <a href="https://theta360.com/s/lD8zKgT91lqWW2gwzvVt5qN4W" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
          <script async src="https://theta360.com/widgets.js" charset="utf-8"></script>
          <h2>口コミ</h2>
        </div>
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
      </div>
    </div>
    <a href="../contact.php?id=$id">○○さんに問い合わせ</a>
BOTTOM;
footer_print();
?>
