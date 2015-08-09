<?php
require_once('lib.php');
header_print('家ログ 詳細', array());

$id = param_get('id');
if (empty($id)) {
	echo('ERROR: wrong page transition!!');
	footer_print();
	die();
}
?>

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
<?php
$owner = "images/owner${id}.png";
$appear = "images/appear${id}.png";

echo("<h3>概観</h3>\n");
echo("<img alt=\"$appear\" src=\"$appear\" width=\"250px\" />\n");
echo("<h3>オーナー</h3>\n");
echo("<img alt=\"$owner\" src=\"$owner\" width=\"250px\" />\n");
?>
			</div>
			<div class="detail_msg">
<script type="text/javascript" src="scripts/rendering-mode.js"></script>
<!--[if IE]><script type="text/javascript" src="scripts/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="scripts/radar.js"></script>
<script type="text/javascript">
window.onload = function() {
	var rc = new html5jp.graph.radar("chart");
	if( ! rc ) { return; }
	var items = [
		["平均", 3, 3, 3, 3, 3, 3],
		["評価", 5, 2, 4, 5, 3, 2],
	];
	var params = {
		aCap: ["都市計画地域", "小学校校区", "地価", "防災情報", "公共施設", "交通情報"]
	}
	rc.draw(items, params);
};
</script>
			<h3>評価</h3>
<div><canvas width="400" height="300" id="chart"></canvas></div>
			<h3>説明</h3>
<span class="detail_text">
　夜空にはたくさんの星が輝いています。しかし、これらの美しい星は永遠に輝き続けるわけではありません。星にも一生があり、生まれたり死んだりしています。ただし、星の一生は人間の一生とはくらべものにならないほど長く、その寿命は人間の一億倍もあります。<br/>
　宇宙のあちこちには、冷たいガスやチリが集まった暗黒星雲と呼ばれるものがあります。暗黒星雲は、その名のとおり光を持たない黒い雲です。実は、この暗黒星雲の中には星の元になる物質がたくさんあります。何かのきっかけで暗黒星雲が縮み始めると、ガスやチリが集まってうずを巻きはじめます。このうず巻きが星のたまごです。 <br/>
　重力によって更に縮んでいくと、うず巻きの中心部が濃くなり、熱くなっていきます。それは、やがて自ら光を放つようになり、赤ちゃん星が誕生します。冬の夜、肉眼でも見ることができるオリオン座の大星雲も、新しい星が生まれている場所です。星雲の中で、数え切れないほどたくさんの星の赤ちゃんが輝いています。 <br/>
</span>
			</div>
			<div style="clear:both;">
				<h3>みんなの評価</h3>
				<img alt="score" src="images/star3.png" />　　<span style="font-size:15px; font-style:bold; color: #0000FF;">いいね　　おきにいり</span>
			</div>
            </li>
        	<!-- 写真 -->
            <li id="tab2" name="tab2">
			<h2>写真</h2>
<?php
echo("<img alt=\"$owner\" src=\"$owner\" style=\"width:250px;margin: 5px;\" />\n");
echo("<img alt=\"$appear\" src=\"$appear\" width=\"250pxmargin: 5px;\" />\n");
echo("<img alt=\"$appear\" src=\"$appear\" width=\"250pxmargin: 5px;\" />\n");
?>
			<h2>室内・周辺</h2>
<blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影2 #theta360 - <a href="https://theta360.com/s/q41fN1dypHKIyAdQUdJz4AeHs" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
<script async src="https://theta360.com/widgets.js" charset="utf-8"></script>
<br/>
<blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影1 #theta360 - <a href="https://theta360.com/s/lD8zKgT91lqWW2gwzvVt5qN4W" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
<script async src="https://theta360.com/widgets.js" charset="utf-8"></script>            </li>
        	<!-- 口コミ -->
            <li id="tab3" name="tab3">
			<h2>口コミ</h2>
<?php
$ncomments = 6; /* XXX */
echo("<div class=\"balloon-wrapper\">\n");
for ($i = 0; $i < $ncomments; $i++) {
	$msg = "また、資源をエネルギーに変換するためには、要らないものを空気中に排出しなくてはなりません。二酸化炭素などの排出物は、大気汚染を引き起こし、やがては地球全体の気温を徐々にあげてしまいます。これが地球温暖化と呼ばれるもので、異常気象や農作物への影響、海水面の上昇、紫外線の問題など、さまざまな問題の原因となっています。人間だけでなく、すべての生き物の住みやすい環境を守るためにも、エネルギーの節約は必要なのです。 <br />";
	 $msg .= "<img alt=\"score\" src=\"images/star${id}.png\" />"; // XXX
	if($i % 2 == 0) {
		echo("<img src=\"./images/icon1.png\" class=\"balloon-left-img\" />\n");
		echo("<p class=\"balloon-left\">$msg</p>\n");
	} else {
		echo("<p class=\"balloon-right\">$msg</p>\n");
		echo("<img src=\"./images/icon1.png\" class=\"balloon-right-img\" />\n");
	}
	echo("<p class=\"clear-p\">&nbsp;</p>\n");
}
echo("</div>\n");
?>
            </li>
        </ul>
    </div>
</div>
<?php
	echo("<a href=\"contact.php?id=$id\">○○さんに問い合わせ</a>");
?>

<?php
footer_print();
?>
