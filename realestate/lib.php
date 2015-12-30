<?php
define('REALESTATE_PAYMENT_TYPE_RENTAL',		1);
define('REALESTATE_PAYMENT_TYPE_SALE',			2);
define('REALESTATE_PAYMENT_TYPE_RENTAL_AND_SALE',	3);

function
realestate_data_dirbase($id)
{

	return 'realestate/' . $id . '/';
}

function
realestate_data_dir($id)
{

	return file_path(realestate_data_dirbase($id));
}

function
realestate_data_url($id, $filename)
{

	return file_url(realestate_data_dirbase($id) . $filename);
}

function
realestate_add($values)
{
	global $USER;

	unset($values['MAX_FILE_SIZE']);
	$values['owner'] = $USER->id;
	return db_record_insert('realestate', $values);
}

function
realestate_image_top_url($r)
{

	if (array_key_exists('id', $r) && array_key_exists('picture', $r)) {
		$id = $r['id'];
		$pic = $r['picture'];
		if (file_exists(realestate_data_dir($id) . $pic))
			return realestate_data_url($id, $pic);
	}
	return IELOG_URI . '/images/appear1.png'; /* XXX: should replace */
}

function
realestate_image_owner_url($r)
{

	return user_picture_url($r['owner']);
}

function
realestate_radar_graph_puts($r)
{
	$url = IELOG_URI . '/scripts/graph-radar';

	// XXX: should reflect actual values...
	echo <<<GRAPH
<script type="text/javascript" src="$url/rendering-mode.js"></script>
<!--[if IE]><script type="text/javascript" src="$url/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="$url/radar.js"></script>
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
<div><canvas width="400" height="300" id="chart"></canvas></div>
GRAPH;
}
?>
