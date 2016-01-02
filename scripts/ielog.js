$(function() {
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

	$('.require-login').on('click', function () {
		post_error(null, 'error', 'requirelogin');
	});

	function
	post_success(json, status, request, cb, param)
	{

		if (json.error !== 'success')
			post_error(request, 'error', json.error);
		else if (cb !== null)
			cb(json.value, param);
	}

	function
	post_error(request, status, err)
	{
		var containerClass = 'ui-state-error';
		var iconClass = 'ui-icon-alert';
		var msg;

		$('#alert-dialog-container').removeClass('ui-state-highlight');
		$('#alert-dialog-icon').removeClass('ui-icon-info');
		$('#alert-dialog-container').removeClass('ui-state-error');
		$('#alert-dialog-icon').removeClass('ui-icon-alert');
		if (err === 'loginexpire' || err === 'requirelogin') {
			if (err == 'requirelogin')
				msg = 'ログインが必要です．';
			else {
				msg = 'ログインが期限切れになりました．';
				msg += '再度ログインして下さい．';
			}
			containerClass = 'ui-state-highlight';
			iconClass = 'ui-icon-info';
		} else if ((! err || err.length === 0) && status === 'error')
			msg = '通信できませんでした．後で再試行して下さい．';
		else
			msg = 'サーバ管理者に連絡して下さい（' + err + '）';
		$('#alert-dialog-container').addClass(containerClass);
		$('#alert-dialog-icon').addClass(iconClass);
		$('#alert-dialog-message').text(msg);
		alertdialog.dialog('open');
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
	$('.like').click(function() {
		var imgurlbase = '/images'; /* XXX */
		var likingimg = imgurlbase + '/liking.png';
		var likeimg = imgurlbase + '/like.png';

		toggle_img($(this), likingimg, likeimg, 'like.php');
	});
} );
