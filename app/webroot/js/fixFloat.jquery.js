(function($){
	$.fn.fixFloat = function(options){
	
		var defaults = {
			enabled: true
		};
		var options = $.extend(defaults, options);

		var offsetTop;		/**Distance of the element from the top of window**/
		var s;				/**Scrolled distance from the top of window through which we have moved**/
		var fixMe = true;
		var repositionMe = true;

		var tbh = $(this);
		var originalOffset = tbh.css('top');	/**Get the actual distance of the element from the top**/

		tbh.css({'position':'absolute'});

        if (options.remove){
			$(window).unbind('scroll', scrollHandler);
            tbh.removeAttr('style');
        } else if(options.enabled){
			$(window).bind('scroll', scrollHandler);
		}


        function scrollHandler(){
            var offsetTop = tbh.offset().top;	/**Get the current distance of the element from the top**/
            var s = parseInt($(window).scrollTop(), 10);	/**Get the from the top of wondow through which we have scrolled**/
            var fixMe = true;
            if(s > offsetTop){
                fixMe = true;
            }else{
                fixMe = false;
            }

            if(s < parseInt(originalOffset, 10)){
                repositionMe = true;
            }else{
                repositionMe = false;
            }

            if(fixMe){
                var cssObj = {
                    'position' : 'fixed',
                    'top' : '0px'
                }
                tbh.css(cssObj);
            }
            if(repositionMe){
                var cssObj = {
                    'position' : 'absolute',
                    'top' : originalOffset
                }
                tbh.css(cssObj);
            }
        }
	};
})(jQuery);
