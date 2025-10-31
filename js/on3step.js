// HTML document is loaded
jQuery( window ).on( "load", function() {
        "use strict";
        
        // autoheight fucntion
        autoheight();
        autoheight1();
        autoheight2();
        autoheight3();
        autoheight4();


        var $ = jQuery;


        // var preloader
        var loader = jQuery( '.preloader, .preloader-white' );
        var bgpreloader = jQuery( '.bg-preloader, .bg-preloader-white' );
        // var navigation
        var menumobile = jQuery( '#main-menu' );
        var navdefault = jQuery( '.navbar-default-white' );
        var Navactive = jQuery( "nav a" );
        var subnav = jQuery( ".subnav" );
        var mainmenudrop = jQuery('#main-menu .menu');
        
        $(window).resize(function() {
          $('section.stretch').each(function() {
            var width = $(this).width();
            var gapWidth;

            $(this).removeAttr('style');

            gapWidth = ($(window).width() - $(this).width()) / 2;

            $(this).css({
              'position': 'relative',
              'left': '-' + gapWidth + 'px',
              'box-sizing': 'border-box',
              'width': $(window).width()
            });
          });
          
          // remove attr style submenu
          var w = $(window).width();
                if(w > 1199 ) {  
                    $('ul.sub-menu').removeAttr('style');  
                    if(subnav.length) {
                      jQuery('.navbar-default-white').addClass('have-sub');
                    }
                }
                
            // remvove attr brand
            if(w < 1199 ){
                $('.navbar-brand.white img.black').show();
                $('.navbar-brand.white img.white').hide();
            }
        });

        $(window).trigger('resize');

        if (mainmenudrop.length > 0) {
          mainmenudrop.children('.has-children').children('a').after('<span class="span-drop"></span>');
        }

        // start function fadeOut preloader when condition window has been load
        loader.fadeOut( 'slow', function() {
            "use strict";
            // opening slideup
            bgpreloader.fadeOut( 1500 );
            // animated transition & scroll onStep
            onStep();
            // stick navbar
            navdefault.sticky();

      // responsive part tap menu
			jQuery('.menu-item span.span-drop, .parent-menu span.span-drop, li span.span-drop')
                  .on('click', function(e){
                    e.stopPropagation();  
                    var $ul = $(this).closest('.menu-item, .parent-menu, #menu-center ul li').find('ul');
                    $ul.slideToggle();
                  });
             
            // mobile icon
            jQuery( ".navbar-toggle" )
                .on( "click", function() {
                    menumobile.toggleClass( 'menu-show' );
                    navdefault.toggleClass( 'fullHeight' );
                } );
        } );
        // end function

        // contact form
        var contactname1 = $('#name-contact-1');
        var contactemail = $('#email-contact, input#email-contact');
        var contactmessage = $('#message-contact');
        var contactsent1 = $('#send-contact-1');
        //form failed succes var
        var successent = jQuery( "#mail_success" );
        var failedsent = jQuery( "#mail_failed" );

    // contact-1 form
	jQuery(function() {
    contactsent1.on('click', function(e) {
      e.preventDefault();
      var e = contactname1.val(),
        a = contactemail.val(),
        s = contactmessage.val(),
        r = !1;
      if (0 == a.length || "-1" == a.indexOf("@") || "-1" == a.indexOf(".")) {
        var r = !0;
        contactemail.css({
          "border": "2px solid #c8b16f"
        });
      } else contactemail.css({
        "border": "2px solid #f1f1f1"
      });
      if (0 == e.length) {
        var r = !0;
        contactname1.css({
          "border": "2px solid #c8b16f"
        });
      } else contactname1.css({
        "border": "2px solid #f1f1f1"
      });
      if (0 == s.length) {
        var r = !0;
        contactmessage.css({
          "border": "2px solid #c8b16f"
        });
      } else contactmessage.css({
        "border": "2px solid #f1f1f1"
      });
      return 0 == r && (contactsent1.attr({
        disabled: "true",
        value: "Sending..."
      }), $.ajax({
        type: "POST",
        url: "send.php",
        data: "name=" + e + "&email=" + a + "&subject=You Got Email&message=" + s,
        success: function(e) {
          "success" == e ? (successent.fadeIn(500)) : (failedsent.html(e).fadeIn(500), contactsent1.removeAttr("disabled").attr("value", "send").remove())
        }
      })), !1
    })
  });

	$('p:empty').remove();
	
	var nocomment = jQuery('p.no-comments');
	if (nocomment.length) {
        jQuery('.comment-info .c-reply i').css('display', 'none');
     }


 } );
// HTML document is loaded end