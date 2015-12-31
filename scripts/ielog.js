$(function() {
	function
	post(url, id, cmd)
	{
		$.ajax({
			url:		url,
			type:		'POST',
			data:		{ id: id, cmd: cmd },
			timeout:	10000,
			success:	function(data) {
					},
			error:		function(request, status, error) {
						alert("error: " + status);
					}
		});
	}

	function
	toggle_img(e, onimg, offimg, url)
	{
		var instanceid = e.data('id');
		var state = e.data('state');
		var id = '#' + e.attr('id');
		var img = $(id + 'img');
		var count = $(id + '-count');
		var ncount = count.text();
		e.effect('bounce', { times: 1, distance: -10 }, 'slow')
		if (state  === 'off') {
			post(url, instanceid, 'on');
			img.attr('src', offimg), ++ncount;
			e.data('state', 'on');
		} else {
			post(url, instanceid, 'off');
			img.attr('src', onimg), --ncount;
			e.data('state', 'off');
		}
		count.text(ncount);
	}
	$('.like').click(function() {
		var imgurlbase = '/images'; /* XXX */
		var likeimg = imgurlbase + '/like.png';
		var likingimg = imgurlbase + '/liking.png';
		toggle_img($(this), likeimg, likingimg, 'like.php');
	});
} );
