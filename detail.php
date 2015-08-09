<?php
require_once('lib.php');
header_print('家ログ 詳細', array());

$id = param_get('id');
if (empty($id)) {
	echo('ERROR: wrong page transition!!');
	footer_print();
	die();
}

$owner = "images/owner${id}.png";
$appear = "images/appear${id}.png";

echo("<img alt=\"$owner\" src=\"$owner\" height=\"200px\" />\n");
echo("<img alt=\"$appear\" src=\"$appear\" height=\"200px\" />\n");

echo("<a href=\"contact.php?id=$id\">○○さんに問い合わせ</a>");
?>

<script type="text/javascript" src="scripts/rendering-mode.js"></script>
<!--[if IE]><script type="text/javascript" src="scripts/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="scripts/radar.js"></script>
<script type="text/javascript">
window.onload = function() {
	var rc = new html5jp.graph.radar("chart");
	if( ! rc ) { return; }
	var items = [
		["平均", 5, 2, 4, 5, 3, 2],
		["評価", 5, 2, 4, 5, 3, 2],
	];
	var params = {
		aCap: ["都市計画地域", "小学校校区", "地価", "防災情報", "公共施設", "交通情報"]
	}
	rc.draw(items, params);
};
</script>
<div><canvas width="400" height="300" id="chart"></canvas></div>

<?php
$ncomments = 10; /* XXX */
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

footer_print();
?>
