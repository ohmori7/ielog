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
	toggle_img(e, on, off, url)
	{
		var instanceid = e.data('id');
		var id = '#' + e.attr('id');
		var img = $(id + 'img');
		var count = $(id + '-count');
		var ncount = count.text();
		e.effect('bounce', { times: 1, distance: -10 }, 'slow')
		if (img.attr('src') === '../images/' + on) {
			post(url, instanceid, 'on');
			img.attr('src', '../images/' + off), ++ncount;
		} else {
			post(url, instanceid, 'off');
			img.attr('src', '../images/' + on), --ncount;
		}
		count.text(ncount);
	}
	$('.like').click(function() {
		toggle_img($(this), 'like.png', 'liking.png', 'like.php');
	});
} );
