jQuery(window).load(function () {

    "use strict";

    jQuery('.filters li:first-child a').trigger('click');

    //Center Slider Paging

    jQuery( '.testimonials-slider' ).each( function() {

        var $this = jQuery( this ),
            TcenterAmount = ( $this.closest( 'section' ).height() / 2 ) - ( $this.find( '.flex-control-paging' ).height() / 2 );

        $this.find( '.flex-control-paging' ).css('top', TcenterAmount - 30);

    });

    // Remove loader

    jQuery('#progress-bar').width('100%');
    jQuery('#loader').hide();

    jQuery('.project-container').isotope({
        // options
        itemSelector: '.project',
        layoutMode: 'masonry'
    });

    setTimeout( function() {

        jQuery( '.isotope' ).isotope( 'layout' );

        }, 1000 );

        setInterval( function() {

        jQuery( '.isotope' ).isotope( 'layout' );

        }, 2000 );

        jQuery( window ).on( 'load resize', function() {

        jQuery( '.isotope' ).isotope( 'layout' );

        setTimeout( function() {

        jQuery( '.isotope' ).isotope( 'layout' );

        }, 1000 );

        setTimeout( function() {

        jQuery( '.isotope' ).isotope( 'layout' );

        }, 2000 );

    });

});

jQuery(document).ready(function () {

    "use strict";

    // Loader bar

    var count = 1;

    jQuery('img').load(function () {

        jQuery('#progress-bar').css('width', count * 170);
        count = count + 1;
    });

    jQuery('#loader').css('padding-top', jQuery(window).height() / 2);

    // Smooth Scroll to internal links

    jQuery('.smooth-scroll, .should-scroll a').smoothScroll({
        offset: jQuery( '#the-navigation' ).height() * -1,
        speed: 800
    });

    if ( jQuery( '#contact' ).length ) {

        jQuery('a[href$="#contact"]').smoothScroll({
            offset: jQuery( '#the-navigation' ).height() * -1,
            speed: 800
        });

    }

    // Initialize Sliders

    jQuery('.home-slider').flexslider({
        directionNav: false
    });

    jQuery('.testimonials-slider').flexslider({
        directionNav: false
    });
    
    jQuery('.post-slider').flexslider({
    	directionNav: false
    });
    
    jQuery('.project-slider').flexslider({
    	directionNav: false
    });

    // Mobile Menu

    jQuery('#mobile-toggle').click(function () {
        if (jQuery('#the-navigation').hasClass('open-nav')) {
            jQuery('#the-navigation').removeClass('open-nav');
        } else {
            jQuery('#the-navigation').addClass('open-nav');
        }
    });

    jQuery('#menu li a').click(function () {
        if (jQuery('#the-navigation').hasClass('open-nav')) {
            jQuery('#the-navigation').removeClass('open-nav');
        }
    });
    
    // Animations
    
    // Turn dynamic animations for iOS devices (because it doesn't look right)

    var iOS = false,
        p = navigator.platform;

    if (p === 'iPad' || p === 'iPhone' || p === 'iPod') {
        iOS = true;
    }
    
    if(iOS == false){
    
		 jQuery('.promo-pic').bind('inview', function (event, visible) {
				if (visible === true) {
					jQuery(this).addClass('animate-in');
				}
			});
			
		jQuery('.trigger-value').bind('inview', function (event, visible) {
				if (visible === true) {
					jQuery(this).addClass('value');
				}
			});
        
    }

    // Adjust slide height for smaller screens

    if (jQuery(window).height() < 760) {
        jQuery('.home-slider .slides li').css('height', jQuery(window).height());
    }


    // Append HTML <img>'s as CSS Background for slides
    // also center the content of the slide

    jQuery('.home-slider .slides li').each(function () {

        var imgSrc = jQuery(this).children('.slider-bg').attr('src');
        jQuery(this).css('background', 'url("' + imgSrc + '")');
        jQuery(this).children('.slider-bg').remove();

        var slideHeight = jQuery(this).height();
        var contentHeight = jQuery(this).children('.slide-content').height();
        var padTop = (slideHeight / 2) - (contentHeight / 2);

        jQuery(this).children('.slide-content').css('padding-top', padTop);

    });

    // Sticky Nav

    jQuery(window).scroll(function () {

        if (jQuery(window).scrollTop() > 300 && !jQuery('#the-navigation').hasClass('inner-nav')) {
            jQuery('#the-navigation').addClass('sticky-nav');
        } else {
            jQuery('#the-navigation').removeClass('sticky-nav');
        }

    });

    // Append .divider <img> tags as CSS backgrounds

    jQuery('section').each(function () {

        if ( jQuery(this).children('.divider-bg').length ) {

            var imgSrc = jQuery(this).children('.divider-bg').attr('src');
            jQuery(this).css('background-image', 'url("' + imgSrc + '")');
            jQuery(this).children('.divider-bg').remove();   

        }

    });

    // Center slider paging

    var centerAmount = (jQuery('.home-slider .slides li').height() / 2) - (jQuery('.home-slider .flex-control-paging').height() / 2);
    jQuery('.home-slider .flex-control-paging').css('top', centerAmount);


    // Initialize Isotope

    jQuery('.filters a').click(function () {
    	var container = jQuery(this).parent().parent('.filters').attr('data-container');
        var selector = jQuery(this).attr('data-filter');
        jQuery('.project-container[data-container="'+container+'"]').isotope({
            filter: selector
        });
        jQuery('.filters[data-container="'+container+'"] a').children('.btn').removeClass('active');
        jQuery(this).children('.btn').addClass('active');
        return false;

    });

    // Project Clicks with AJAX call

    jQuery('.project-btn-holder a').click(function (event) {

        if ( jQuery( this ).closest( '.project' ).hasClass( 'noajax' ) ) {

            return; // we want normal behaviour

        }

        event.preventDefault();

        var projectContainer = jQuery(this).closest('.projects-instance').children('.ajax-container').attr('data-container');

        if (jQuery('.ajax-container[data-container="'+projectContainer+'"]').hasClass('open-container')) {
            jQuery('.ajax-container[data-container="'+projectContainer+'"]').addClass('closed-container');
            jQuery('.ajax-container[data-container="'+projectContainer+'"]').removeClass('open-container');
        }

        var fileID = jQuery(this).attr('href');

        if (fileID != null) {
            jQuery('html,body').animate({
                scrollTop: jQuery('.ajax-container[data-container="'+projectContainer+'"]').offset().top - 100
            }, 500);

        }
        
        jQuery('.ajax-container[data-container="'+projectContainer+'"]').load(fileID+" .project-body", function(){

            jQuery('.ajax-container[data-container="'+projectContainer+'"]').find("img[data-original]:not(img.pe-lazyload-inited)").peLazyLoading();

        	jQuery('.ajax-container[data-container="'+projectContainer+'"]').children('.project-body').append("<div class=\"row\"><div class=\"close-project\"><i class=\"typcn typcn-delete-outline\"></i></div></div>");
        	jQuery('.ajax-container[data-container="'+projectContainer+'"]').addClass('open-container');
        	jQuery('.project-slider').flexslider({
                directionNav: false
            });
            jQuery('.ajax-container[data-container="'+projectContainer+'"]').removeClass('closed-container');

            jQuery( '.video-container' ).fitVids();
            
            jQuery('.close-project').click(function () {
                jQuery('.ajax-container[data-container="'+projectContainer+'"]').addClass('closed-container');
                jQuery('.ajax-container[data-container="'+projectContainer+'"]').removeClass('open-container');
                jQuery('html,body').animate({
                    scrollTop: jQuery('.project-container[data-container="'+projectContainer+'"]').offset().top - 100
                }, 500);
                setTimeout(function () {
                    jQuery('.ajax-container[data-container="'+projectContainer+'"]').html('');
                }, 1000);
            });
        });

    });

});