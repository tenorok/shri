jQuery.fn.placeholder = function(options){
    if(!jQuery.browser.webkit)
    {
        var options = jQuery.extend({
            phstyle: '',
            phclass: ''
        }, options);

        var passes = new Array();
        var curpass = 0;

        function retype(obj, type)
        {
            var marker = $('<span />').insertBefore(obj);
            $(obj).detach().attr('type', type).insertAfter(marker);
            marker.remove();
        }

        function getfocus(obj)
        {
            jQuery(obj).focus();
        }

        return this.each(function(){

            var defCss = this.style.cssText;

            if(jQuery(this).attr('type') == 'password')
            {
                passes[curpass++] = jQuery('input').index(this);
                retype(this, 'text');
            }
            
            jQuery(this).val(jQuery(this).attr('placeholder'));
            jQuery(this).css(options.phstyle);
            jQuery(this).addClass(options.phclass);

            jQuery(this)
                .focus(function(){
                    this.style.cssText = defCss;
                    jQuery(this).removeClass(options.phclass);

                    if(jQuery.trim(jQuery(this).val()) == jQuery(this).attr('placeholder'))
                    {
                        if(jQuery.inArray(jQuery('input').index(this), passes) >= 0)
                            retype(this, 'password');
                        jQuery(this).val('');
                        
                        var obj = this;
                        setTimeout(function(){
                            jQuery(obj).focus();
                        }, 10);
                    }
                })
                .blur(function(){
                    if(jQuery.trim(jQuery(this).val()) == '')
                    {
                        if(jQuery.inArray(jQuery('input').index(this), passes) >= 0)
                            retype(this, 'text');
                        jQuery(this).val(jQuery(this).attr('placeholder'));
                        jQuery(this).css(options.phstyle);
                        jQuery(this).addClass(options.phclass);
                    }
                });

        });
    }
};