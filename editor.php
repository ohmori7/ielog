<?php
css_file_add('scripts/elrte/css/elrte.full.css');
script_file_add('scripts/elrte/js/elrte.full.js');
script_file_add('scripts/elrte/js/i18n/elrte.jp.js');

function
editor_add($id, $width = 800, $height = 120, $lang = 'jp')
{
	$script = "
	$().ready(function() {
		var opts = {
			cssClass:	'el-rte',
			lang:		'jp',
			width:		$width,
			height:		$height,
			toolbar:	'normal',
			cssfiles:	['/scripts/elrte/css/elrte-inner.css']
		}
		$('#$id').elrte(opts);
	});";
	script_code_add($script);
}
?>
