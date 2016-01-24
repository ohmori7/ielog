	var alertdialog;

	alertdialog = $('#alert-dialog').dialog({
		autoOpen:	false,
		modal:		true,
		buttons: {
				'close': function () {
					$(this).dialog('close');
				}
			}
	});

	function
	alert_dialog_open(msg, error)
	{
		var containerClass;
                var iconClass;

		if (error) {
			containerClass = 'ui-icon-alert';
			iconClass = 'ui-state-error';
		} else {
			containerClass = 'ui-state-highlight';
			iconClass = 'ui-icon-info';
		}
		$('#alert-dialog-container').removeClass('ui-state-highlight');
		$('#alert-dialog-icon').removeClass('ui-icon-info');
		$('#alert-dialog-container').removeClass('ui-state-error');
		$('#alert-dialog-icon').removeClass('ui-icon-alert');
		$('#alert-dialog-container').addClass(containerClass);
		$('#alert-dialog-icon').addClass(iconClass);
		$('#alert-dialog-message').text(msg);
		alertdialog.dialog('open');
	}

	$('.require-login').on('click', function () {
		post_error(null, 'error', 'requirelogin');
	});

	function
	post_success(json, status, request, cb, param)
	{

		if (json.status !== 'OK')
			post_error(request, 'error', json.status);
		else if (cb !== null)
			cb(json.results, param);
	}

	function
	post_error(request, status, err)
	{
		var iserror = true;
		var msg;

		if (err === 'loginexpire' || err === 'requirelogin' ||
		    err === 'emptydata') {
			if (err == 'emptydata')
				msg = 'データが入力されていません．';
			else if (err == 'requirelogin')
				msg = 'ログインが必要です．';
			else {
				msg = 'ログインが期限切れになりました．';
				msg += '再度ログインして下さい．';
			}
			iserror = false;
		} else if ((! err || err.length === 0) && status === 'error')
			msg = '通信できませんでした．後で再試行して下さい．';
		else
			msg = 'サーバ管理者に連絡して下さい（' + err + '）';
		alert_dialog_open(msg, iserror);
	}

	function
	post(url, submitdata, cb, param)
	{
		$.ajax({
			url:		url,
			type:		'POST',
			cache:		false,
			data:		submitdata,
			dataType:	'json',
			timeout:	10000,
			success:	function(json, status, request) {
						post_success(json, status,
						    request, cb, param);
					},
			error:		post_error
		});
	}

	function
	top_picture_callback(data, param)
	{
		var msg;

		msg = param.name + 'を概観の画像として設定しました．';
		alert_dialog_open(msg, false);
	}

	function
	top_picture_set(id, file)
	{
		data = new Object();
		data.id = id;
		data.file = file.name;
		post('/realestate/pic.php', data, top_picture_callback, file);
	}

$(function() {
	function
	toggle_img_callback(data, param)
	{
		var id = '#' + param.element.attr('id');
		var img = $(id + '-img');
		var count = $(id + '-count');

		param.element.data('state', param.state);
		img.attr('src', param.image);
		/*
		 * here is a race, e.g., the number of likes is zero while
		 * we are liking it when a user uses multiple web browser.
		 * we, however, do not be so strict.
		 */
		count.text(data);
	}

	function
	toggle_img(e, onimg, offimg, url)
	{
		var data, param;

		e.effect('bounce', { times: 1, distance: -10 }, 'slow')
		data = new Object();
		data.id = e.data('id');
		param = new Object();
		param.element = e;
		if (e.data('state') === 'off')
			data.cmd = param.state = 'on', param.image = onimg;
		else
			data.cmd = param.state = 'off', param.image = offimg;
		post(url, data, toggle_img_callback, param);
	}

	$('.likable').click(function() {
		var imgurlbase = '/images'; /* XXX */
		var likingimg = imgurlbase + '/liking.png';
		var likeimg = imgurlbase + '/like.png';

		toggle_img($(this), likingimg, likeimg, 'like.php');
	});

	function
	comment_callback(data, param)
	{

		param.elrte('val', '');
	}

	$('.comment').click(function() {
		var url = '/comment/edit.php'; /* XXX */
		var eid = $(this).data('element-id');
		var e = $('#' + eid);
		data = new Object();
		data.id = $(this).data('id');
		data.cmd = 'add'; /* XXX */
		data.comment = e.elrte('val');
		post(url, data, comment_callback, e);
	});
});
