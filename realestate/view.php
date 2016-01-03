<?php
require_once('../lib.php');
require_once('../editor.php');
require_once('lib.php');
require_once('../comment/lib.php');

user_require_login();

header_print(array());

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
$comments = comment_get($r['id']);
$feedback = realestate_feedback_html($r);
if ($r['owner'] !== $USER->id)
	$editlink = '';
else {
	$editlink = <<<EDITLINK
<a href="edit.php?id={$r['id']}">
  <img alt="edit" src="../images/edit.png" width="24" height="24" />
</a>
EDITLINK;
}
echo <<<TOP
        <div id="menu">
          <!-- no menu for this page -->
        </div>
        <div id="content">
          <!-- 詳細情報 -->
          <h2>詳細情報$editlink</h2>
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

$feedback
              <div class="clearfix">
                <div style="float: left; width: 50%;">
                  <h3>住所</h3>
                  $address
                </div>
                <div style="float: left; margin-left: 5%; width: 20%;">
                  <h3>契約形態</h3>
                  $contract
                </div>
                <div style="float: left; margin-left: 5%; width: 20%;">
                  <h3>築年数</h3>
                  $age
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix">
            <h3>概要</h3>
            {$r['abstract']}
            <h3>説明</h3>
            {$r['description']}
            <h3>写真</h3>
            <img alt="$appear" src="$appear" width="250" style="margin: 5;" />
            <h3>室内・周辺</h3>
            <blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影2 #theta360 - <a href="https://theta360.com/s/q41fN1dypHKIyAdQUdJz4AeHs" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
            <script async src="https://theta360.com/widgets.js" charset="utf-8"></script><br/>
            <blockquote data-width="500" data-height="375" class="ricoh-theta-spherical-image" >#code4tottori 追い込みシータ撮影1 #theta360 - <a href="https://theta360.com/s/lD8zKgT91lqWW2gwzvVt5qN4W" target="_blank">Spherical Image - RICOH THETA</a></blockquote>
            <script async src="https://theta360.com/widgets.js" charset="utf-8"></script>
            <h3>コメント</h3>
          </div>
          <div class="balloon-wrapper clearfix">
MIDDLE;
$i = 0;
foreach ($comments as $c) {
	$user = array('id' => $c['user'], 'picture' => $c['picture']);
	$userpic = user_picture_url($user);
	$side = ($i % 2 === 0) ? 'left' : 'right';
	echo <<<COMMENT

          <img src="$userpic" class="balloon-$side-img" />
          <p class="balloon-$side">{$c['comment']}</p>
	  <p class="clear-p">&nbsp;</p>
COMMENT;
	$i++;
}
editor_add('comment');
echo <<<BOTTOM

          </div>
          <div style="width: 60%; margin: 0 auto;">
            <form action="../comment/edit.php" method="post">
              <textarea id="comment"></textarea>
              <button type="button" class="comment" data-id="$id" data-element-id="comment">
                投稿
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
BOTTOM;
footer_print();
?>
