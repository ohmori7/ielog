$(function() {
	function
	on_or_off(e, on, off)
	{
		var id = '#' + e.attr('id');
		var img = $(id + 'img');
		var count = $(id + '-count');
		var ncount = count.text();
		e.effect('bounce', { times: 1, distance: -10 }, 'slow')
		if (img.attr('src') === '../images/' + on)
			img.attr('src', '../images/' + off), ++ncount;
		else
			img.attr('src', '../images/' + on), --ncount;
		count.text(ncount);
	}
	$('.like').click(function() {
		on_or_off($(this), 'like.png', 'liking.png');
	});
	$('.favorite').click(function() {
		on_or_off($(this), 'favorite.png', 'favoriting.png');
	});
} );
