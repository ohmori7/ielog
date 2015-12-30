$(function() {
	/* like */
	$('.like').click(function() {
		var id = '#' + $(this).attr('id');
		var img = $(id + 'img');
		var count = $(id + '-count');
		var ncount = count.text();
		$(this).effect('bounce', { times: 1, distance: -10 }, 'slow')
		if (img.attr('src') === '../images/like.png')
			img.attr('src', '../images/liking.png'), ++ncount;
		else
			img.attr('src', '../images/like.png'), --ncount;
		count.text(ncount);
	});
} );
