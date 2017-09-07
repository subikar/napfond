(function(jQuery) {
    jQuery(function() {
        jQuery('.jcarousel').jcarousel();

        jQuery('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                jQuery(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQuery(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        jQuery('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                jQuery(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQuery(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });
			
			jQuery('.jcarousel-control-prev1')
            .on('jcarouselcontrol:active', function() {
                jQuery(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQuery(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        jQuery('.jcarousel-control-next1')
            .on('jcarouselcontrol:active', function() {
                jQuery(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                jQuery(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });

        jQuery('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                jQuery(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                jQuery(this).removeClass('active');
            })
            .jcarouselPagination();
    });
})(jQuery);
