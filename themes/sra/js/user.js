(function($, Drupal, drupalSettings) {
    
    'use strict';
    
    Drupal.behaviors.mybehavior = {
        attach: function(context, settings) {
            /*Toggle Menu Left*/
            let slide_toggle = $('.header-content .sidebar-toggle');
            slide_toggle.once().click(function() {
                $('.profile-wrapper').toggleClass('open');
            });
            
        }
    };
    
})(jQuery, Drupal, drupalSettings);