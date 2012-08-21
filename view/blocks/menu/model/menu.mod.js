var menuMod = {

	init: function(anchor) {

		$('.menu__link_sub_yes')
			.unbind('click')
			.click(menuMod.toggleSubMenu);
		
		$('.menu__link, .menu__submenu-link')
			.not('.menu__link_sub_yes')
			.unbind('click')
			.click(menuMod.gotoAnchor);

		if(anchor !== undefined) {

			var menu = anchor.split('-')[0],
				link = $('.menu__link_sub_yes[href="#/' + menu + '"]');

			if(link.next('.menu__submenu').is(':hidden'))
				link.trigger('click');
		}
	},

	toggleSubMenu: function() {

		var subMenu = $(this).next('.menu__submenu'),
			arrow   = $(this).children('.menu__link-arrow');

		if($(subMenu).is(':hidden')) {

			$(subMenu).slideDown('fast');
			$(arrow).html('&#9650;');
		}
		else {

			$(subMenu).slideUp('fast');
			$(arrow).html('&#9660;');
		}

		return false;
	},

	gotoAnchor: function() {
		
		var href         = $(this).attr('href'),
			anchorId     = href.substring(1),
			anchorPosTop = $('(h1, h2)[id="' + anchorId + '"]').offset().top;
		
		$('html, body').animate({
			scrollTop: anchorPosTop
		}, 'fast', function() {
			location.hash = href;
		});

		return false;
	}
};