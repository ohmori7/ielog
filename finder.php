<?php
require_once('lib.php');
require_once('realestate/lib.php');

user_require_login();
$id = param_get_int('id');
if (! realestate_is_editable(realestate_get($id)))
	error('権限がありません．');
$path = 'scripts/elfinder';

$csses = array(
	'common.css',
	'dialog.css',
	'toolbar.css',
	'navbar.css',
	'statusbar.css',
	'contextmenu.css',
	'cwd.css',
	'quicklook.css',
	'commands.css',
	'fonts.css',
	'theme.css'
	);
$scripts = array(
	'js/elFinder.js',
	'js/elFinder.version.js',
	'js/jquery.elfinder.js',
	'js/elFinder.resources.js',
	'js/elFinder.options.js',
	'js/elFinder.history.js',
	'js/elFinder.command.js',
	'js/ui/overlay.js',
	'js/ui/workzone.js',
	'js/ui/navbar.js',
	'js/ui/dialog.js',
	'js/ui/tree.js',
	'js/ui/cwd.js',
	'js/ui/toolbar.js',
	'js/ui/button.js',
	'js/ui/uploadButton.js',
	'js/ui/viewbutton.js',
	'js/ui/searchbutton.js',
	'js/ui/sortbutton.js',
	'js/ui/panel.js',
	'js/ui/contextmenu.js',
	'js/ui/path.js',
	'js/ui/stat.js',
	'js/ui/places.js',
	'js/commands/back.js',
	'js/commands/forward.js',
	'js/commands/reload.js',
	'js/commands/up.js',
	'js/commands/home.js',
	'js/commands/copy.js',
	'js/commands/cut.js',
	'js/commands/paste.js',
	'js/commands/open.js',
	'js/commands/rm.js',
	'js/commands/info.js',
	'js/commands/duplicate.js',
	'js/commands/rename.js',
	'js/commands/help.js',
	'js/commands/getfile.js',
	'js/commands/mkdir.js',
	'js/commands/mkfile.js',
	'js/commands/upload.js',
	'js/commands/download.js',
	'js/commands/edit.js',
	'js/commands/quicklook.js',
	'js/commands/quicklook.plugins.js',
	'js/commands/extract.js',
	'js/commands/archive.js',
	'js/commands/search.js',
	'js/commands/view.js',
	'js/commands/resize.js',
	'js/commands/sort.js',
	'js/commands/netmount.js',
	'js/i18n/elfinder.ar.js',
	'js/i18n/elfinder.bg.js',
	'js/i18n/elfinder.ca.js',
	'js/i18n/elfinder.cs.js',
	'js/i18n/elfinder.de.js',
	'js/i18n/elfinder.el.js',
	'js/i18n/elfinder.en.js',
	'js/i18n/elfinder.es.js',
	'js/i18n/elfinder.fa.js',
	'js/i18n/elfinder.fr.js',
	'js/i18n/elfinder.hu.js',
	'js/i18n/elfinder.it.js',
	'js/i18n/elfinder.jp.js',
	'js/i18n/elfinder.ko.js',
	'js/i18n/elfinder.nl.js',
	'js/i18n/elfinder.no.js',
	'js/i18n/elfinder.pl.js',
	'js/i18n/elfinder.pt_BR.js',
	'js/i18n/elfinder.ru.js',
	'js/i18n/elfinder.sl.js',
	'js/i18n/elfinder.sv.js',
	'js/i18n/elfinder.tr.js',
	'js/i18n/elfinder.zh_CN.js',
	'js/i18n/elfinder.zh_TW.js',
	'js/i18n/elfinder.vi.js',
	'js/jquery.dialogelfinder.js',
	'js/proxy/elFinderSupportVer1.js'
	);

foreach ($csses as $css)
	css_file_add("$path/css/$css");
foreach($scripts as $script)
	script_file_add("$path/$script");
	$code = "
		$(function() {
			$('#elfinder').elfinder({
				url:		'connector.php?id=$id',
				soundPath:	'$path/sounds',
			});
		});";
script_code_add($code);
css_file_add('scripts/jquery-ui/jquery-ui.theme.min.css');
header_print();
?>
	<div id="elfinder"></div>
<?php
footer_print();
?>
