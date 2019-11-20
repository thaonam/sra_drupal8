(function ($, Drupal) {
  Drupal.behaviors.sra = {
    attach: function (context, settings) {
      // Custom about SRA menu
      if ($('#block-mainnavigation').length) {
        var title = $('.navbar-nav > li.active-trail > a.active-trail ').html();
        var text_title = title.split('<span class="caret"></span>').join('');
        text_title.replace(/\s/g, '');
        text_title = text_title.toLowerCase();
        $('#block-mainnavigation').find('h2#block-mainnavigation-menu').html(title);
        $('#block-mainnavigation').addClass(text_title);
      }
      
      // Add class to body.
      $("#block-system-main").width($(".container").width());
      $("body").addClass("page-memberscirculars");
      
      $('header#navbar ul.menu.menu--main.nav.navbar-nav').once().after(' <div id="form-search"><i class="fa fa-search" aria-hidden="true"></i></div>');
      $('div#form-search').click(function() {
        $('div#block-sra-search').toggleClass('show');
      })

      $('ul.menu.menu--main.nav.navbar-nav li a').removeAttr('data-toggle');

      // Move div
      if ($(window).width() <= 767) {
        $('.navbar-header.nop').once().append('<div class="block-top"></div>');
        $('div#block-sra-search').once().appendTo($('div.block-top'));
        $('nav#block-useraccountmenu').once().appendTo($('div.block-top'));
        $('div#form-search').once().appendTo($('div.block-top'));
        $('div#navi').once().prepend($('.block-top'));
      }
     
      
      // Hash Link Active Tabs Bootstrap
      // Javascript to enable link to tab
      var hash = document.location.hash;
      var prefix = "tab_";
      if (hash) {
        $('.nav-tabs a[href="' + hash.replace(prefix, "") + '"]').tab('show');
      }
      
      // Change hash for page-reload
      $('.nav-tabs a').on('shown', function (e) {
        window.location.hash = e.target.hash.replace("#", "#" + prefix);
      });
     
      var a = $('div#block-views-block-node-functions-block-16 table.table.table-hover.table-striped table > tbody > tr');
  
      // a.each(function() {
      //   var b = $(this).find('td').length;
      //   if (b == 1) {
      //     $(this).css('display', 'none');
      //   }
       
      // })
      // if (.find($('td'))) {
      //   $('div#block-views-block-node-functions-block-16 table.table.table-hover.table-striped table > tbody > tr').css('display', 'none');
      // }\
      if($('a.round.grow.mail').length){
        $('a.round.grow.mail').attr('href', '/contact-us');
      }
    }
  };
})
(jQuery, Drupal);

jQuery( document ).ready(function() {
  if (jQuery('a[aria-controls="webform-terms-of-service-terms_of_service--description"]').length) {
    let a_href = jQuery('a[aria-controls="webform-terms-of-service-terms_of_service--description"]').closest('.form-type-webform-terms-of-service ').find('#edit-terms-of-service--description a').attr('href');
    jQuery('a[aria-controls="webform-terms-of-service-terms_of_service--description"]').after('<a href="' + a_href + '">' + jQuery('a[aria-controls="webform-terms-of-service-terms_of_service--description"]').html() + '</a>');
    jQuery('a[aria-controls="webform-terms-of-service-terms_of_service--description"]').hide();
  }
  if (jQuery('div.form-item-type-of-reinsurance-operation-our-company’s-reinsurance-portfolio-').length) {
    jQuery('div.form-item-type-of-reinsurance-operation-our-company’s-reinsurance-portfolio- label').append(jQuery(' #edit-type-of-reinsurance-operation-our-companys-reinsurance-portfolio---description'));

  }
  jQuery('nav#block-membersarea-2 ul.m enu li:first-child').before(jQuery('.page-memberscirculars aside#sidebar-right section#block-helloblock'));
});
