(function($){
    /**
     * If input has class==required it needs a value. If the attribute data-pattern is set, then the value is checked with regex against the pattern.
     * @author Hans Westman
     */
    $.fn.validateInputs = function(){
        var requiredClass = 'required';
        var invalidClass = 'invalid';

        this.removeClass(invalidClass);
        this.each(function(){
            var $input = $(this);
            if($input.hasClass(requiredClass)){
                if($input.is('[type="checkbox"]')){
                    if($input.is(':checked')){
                        $input.addClass(invalidClass);
                    }
                }
                else if($input.is('[type="radio"]')){
                    if($('input[type="radio"][name="'+$input.attr('name')+'"]:checked').length < 1){
                        $input.addClass(invalidClass);
                    }
                }
                else{
                    if($input.val().length < 1){
                        $input.addClass(invalidClass);
                    }
                }
            }

            if(typeof($input.attr('data-pattern')) !== 'undefined'){
                if(!$input.val().match(new RegExp($input.attr('data-pattern'),'i'))){
                    $input.addClass(invalidClass);
                }
            }
        });
        return this;
    };

    $('#post').submit(function(ev){
        var inputs = $('input,textarea,select');
        inputs.validateInputs();
        if(inputs.filter('.invalid').length > 0){
            ev.preventDefault();
            return false;
        }
        else{
            return true;
        }
    });
})(jQuery);



