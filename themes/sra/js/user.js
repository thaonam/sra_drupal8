<<<<<<< HEAD
(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.behaviors.mybehavior = {
    attach: function (context, settings) {
      /*Toggle Menu Left*/
      let slide_toggle = $('.header-content .sidebar-toggle');
      slide_toggle.once().click(function () {
        $('.profile-wrapper').toggleClass('open');
      });
    }
  };

})(jQuery, Drupal, drupalSettings);
jQuery(document).ready(function () {
  jQuery('.menu--menu-user .dropdown-submenu').each(function () {
    jQuery(this).click(function () {
      jQuery(this).find('svg.fa-angle-right').slideToggle();
      jQuery(this).find('svg.fa-angle-down').slideToggle();
      jQuery(this).next('.sub-menu').slideToggle();
      return false;
    });
  });
});
=======
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
>>>>>>> eaf061d6c2ee98e2c74004a332853f594a80da83
