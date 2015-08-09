<?php
require_once('lib.php');
header_print('家ログ', array());
?>
	<script src="./scripts/list.js"></script>
	<ol>
	<?php
	for ($i = 1; $i <= 5; $i++) {
		echo("<li>");
		echo("<div class=\"info_area\">");
		echo("<div class=\"button_area\"><input type=\"button\" id=\"btn$i\" value=\"詳細\" onclick=\"javascript:show_detail($i);\" /></div>");
		echo("<div class=\"picture_area\"><img class=\"pict_owner\" src=\"./images/owner$i.png\" />".
			"<img class=\"pict_app\" src=\"./images/appear$i.png\" /></div>");
		echo("<div class=\"desc_area\">".
			"<img src=\"./images/star$i.png\" /><br/>".
			"省エネルギーという言葉を聞いたことがあるでしょう。部屋の電気をこまめに消したり、冷房や暖房の温度設定を控え目にしたり、エレベーターや自動車をやめて自分の足で歩くように心がけたりすることです。今は、身近な省エネルギーから広がって、社会全体で取り組んでいこうという運動になっています。 </div>");
		echo("</div>");
		echo("</li>\n");
	}
	?>
	</ol>
<?php
footer_print();
?>
