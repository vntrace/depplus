(function($){
	$('.cat-parent').hover(function(){
		$(this).find('.children').show();
	}, function(){
		$(this).find('.children').hide();
	});
})(jQuery);