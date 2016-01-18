<?php
define('REALESTATE_CONTRACT_TYPE_RENTAL',		1);
define('REALESTATE_CONTRACT_TYPE_SALE',			2);
define('REALESTATE_CONTRACT_TYPE_RENTAL_AND_SALE',	3);

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
realestate_contracts()
{

	return array(
	    REALESTATE_CONTRACT_TYPE_RENTAL =>		'賃貸',
	    REALESTATE_CONTRACT_TYPE_SALE =>		'売買',
	    REALESTATE_CONTRACT_TYPE_RENTAL_AND_SALE =>	'賃貸・売買'
	    );
}

function
realestate_contract_name($type)
{
	$names = realestate_contracts();

	return $names[$type];
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
realestate_get($id = null)
{
	global $USER;

	if (user_is_loggedin()) {
		$likedselect = "COUNT(rlself.id) AS liked, ";
		$likedjoin = "LEFT JOIN realestate_like AS rlself
		    ON r.id = rlself.realestate AND rlself.user = {$USER->id}";
	}
	if ($id !== null)
		$where = "WHERE r.id = $id";
	else
		$where = '';
	$sql = "
	    SELECT r.*, $likedselect
	        COUNT(rl.id) AS likes
	    FROM realestate AS r
	        $likedjoin
	        LEFT JOIN realestate_like AS rl ON r.id = rl.realestate
	    $where
	    GROUP BY r.id";
	$rs = db_records_get_sql($sql);
	if ($rs !== false && $id !== null)
		$rs = array_pop($rs);
	return $rs;
}

function
realestate_like_values($realestate)
{
	global $USER;

	return array(
	    'user' => $USER->id,
	    'realestate' => $realestate
	    );
}

function
realestate_like($realestate)
{
	$values = realestate_like_values($realestate);

	$r = db_record_get('realestate_like', $values);
	if ($r !== false)
		return false;
	return db_record_insert('realestate_like', $values);
}

function
realestate_unlike($realestate)
{
	$values = realestate_like_values($realestate);

	return db_record_delete('realestate_like', $values);
}

function
realestate_like_html($r, $likeable = false)
{
	$id = $r['id'];
	$eid = "realestate$id-like";

	if ($r['liked']) {
		$like = 'on';
		$likeimg = image_url('liking.png');
	} else {
		$like = 'off';
		$likeimg = image_url('like.png');
	}
	if ($likeable)
		$class = 'class="like likable"';
	else
		$class = 'class="like"';

	return <<<HTML
	        <div id="{$eid}" $class data-id="$id" data-state="$like">
                  <img id="${eid}-img" src="$likeimg" width="24" height="24" />
                  <span>いいね！</span>
                  <span id="${eid}-count">{$r['likes']}</span>
                </div>
HTML;
}

function
realestate_comment_count_html($r)
{

	$img = image_url('comment.png');
	$count = db_records_count('comment', array('realestate' => $r['id']));
	return <<<HTML
                <div class="comment-count">
                  <img src="$img" width="24" height="24"/>
                  <span>コメント</span>
                  <span id="realestate{$r['id']}-comment-count">{$count}</span>
                </div>
HTML;
}

function
realestate_feedback_html($r, $likable = false)
{

	$img = image_url('star3.png'); /* XXX */
	$like = realestate_like_html($r, $likable);
	$comment = realestate_comment_count_html($r);
	return <<<HTML
              <div class="clearfix">
                <div id="realestate{$r['id']}-score" class="score">
                  <img alt="score" src="$img" />
                </div>
                <div>
$like
$comment
                </div>
              </div>
HTML;
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
	return image_url('noimage.png');
}

function
realestate_image_owner_url($r)
{

	return user_picture_url($r['owner']);
}

function
realestate_age($r)
{
	$now = new DateTime('now', new DateTimeZone('Japan'));
	$built = new DateTime($r['builtdate'], new DateTimeZone('Japan'));

	$age = $now->diff($built)->format('%y');
	if ((int)$age === 0)
		$age = '新築';
	else
		$age .= '年';
	return $age;
}

function
realestate_radar_graph_puts($r)
{
	$url = IELOG_URI . '/scripts/graph-radar';

	// XXX: should reflect actual values...
	echo <<<GRAPH
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
