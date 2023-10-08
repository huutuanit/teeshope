(function($){
	$(document).ready(function(){
		// nav area
		var nav = $('#nav');
		var content = $('#content .content-area');
		
		/* nav.find('.main-nav-item').click(function(){
			var current = $(this).parent();
			
			$(this).siblings('.sub-nav').slideToggle(function(){
				current.toggleClass('active');
			});
		});
		nav.find('.sub-nav-item').click(function(){
			nav.find('.sub-nav-item').removeClass('active');
			$(this).addClass('active');
			content.children($(this).attr('href')).fadeIn().siblings('.content-section').hide();
			return false;
		}); */
		nav.find('.main-nav-item').click(function(){
			var current = $(this).parent();
			
			$(this).siblings('.sub-nav').slideToggle(function(){
				current.toggleClass('active');
			});
		});
		nav.find('.sub-nav-item').click(function(){
			nav.find('.sub-nav-item').removeClass('active');
			$(this).addClass('active');
			scroll_top();
			content.children($(this).attr('href')).fadeIn().siblings('.content-section').hide();
		}); 

		var url = window.location.hash;
		
		if ( url ) {
			console.log(url);
			$('.sub-nav-item').removeClass('active');

			$(".sub-nav-item[href='" + url + "']").addClass( 'active' )

			$( '.content-section' ).hide();
			$( '.content-section' + url ).show();
			var $parent_manu_item = $(".sub-nav-item[href='" + url + "']").closest('.sub-nav').closest('.nav-item').find('.main-nav-item');
			var $parent_nav = $(".sub-nav-item[href='" + url + "']").closest('.sub-nav').closest('.nav-item');
			if(!$parent_nav.hasClass('active')){
				$parent_manu_item.trigger('click');
			} 
			
			/* var current = $(this).parent();
			
			$(this).siblings('.sub-nav').slideToggle(function(){
				current.toggleClass('active');
			}); */
			
		}
		function scroll_top(){
			$('html,body').animate({scrollTop:0}, 600);	
		}
		$('.popup-link').magnificPopup({
		  type: 'image'
		  // other options
		});

	});
})(jQuery);