$(function() {
	function
	post_callback_error(request, status, error)
	{

		alert("error: " + status);
	}

	function
	post(url, id, cmd, callback, param)
	{
		$.ajax({
			url:		url,
			type:		'POST',
			data:		{ id: id, cmd: cmd },
			timeout:	10000,
			success:	function(response, status, xmlrequest)
					{
						/* XXX: should handle error. */
						callback(response, param);
					},
			error:		post_callback_error
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
		var instanceid = e.data('id');
		var param;

		e.effect('bounce', { times: 1, distance: -10 }, 'slow')
		param = new Object();
		param.element = e;
		if (e.data('state') === 'off')
			param.state = 'on', param.image = onimg;
		else
			param.state = 'off', param.image = offimg;
		post(url, instanceid, param.state, toggle_img_callback, param);
	}
	$('.like').click(function() {
		var imgurlbase = '/images'; /* XXX */
		var likingimg = imgurlbase + '/liking.png';
		var likeimg = imgurlbase + '/like.png';

		toggle_img($(this), likingimg, likeimg, 'like.php');
	});
} );
