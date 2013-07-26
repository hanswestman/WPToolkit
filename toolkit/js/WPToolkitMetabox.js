(function($){
	
	$('.js-add-another-field').on('click', function(ev){
		ev.preventDefault();
		var button = $(this);
		var container = $(this).parents('div').eq(0);
		var fieldsets = container.find('fieldset');
		var limit = container.data('field-limit');
		if(fieldsets.length >= limit){
			button.remove();
			return false;
		}
		var clone = fieldsets.eq(0).clone();
		clone.find('input,select,textarea').val('');
		clone.insertAfter(fieldsets.eq(fieldsets.length - 1));
		if(fieldsets.length + 1 >= limit){
			button.remove();
		}
	});
	
	 $('.js-wptoolkit-colorpicker').each(function(){$(this).wpColorPicker();});
	 $('.js-wptoolkit-datepicker').datepicker({ dateFormat: "yy-mm-dd" });
	
})(jQuery);