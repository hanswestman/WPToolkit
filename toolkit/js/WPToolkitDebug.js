(function($){
	
	$(document).on('ready', function(){
		$('#wp-admin-bar-wptoolkit_toggle_debug').on('click', function(ev){
			ev.preventDefault();
			$('.wptoolkit-debug-content').slideToggle();
		});

		$('.wptoolkit-debug-change-tab').on('click', function(ev){
			ev.preventDefault();
			$('.wptoolkit-debug-tab').removeClass('active').filter('[data-id="' + $(this).attr('data-id') + '"]').addClass('active');
			$('.wptoolkit-debug-change-tab').parent().removeClass('active');
			$(this).parent().addClass('active');
		});
	});
	
})(jQuery);