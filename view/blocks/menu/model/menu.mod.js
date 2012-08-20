var menuMod = {

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
		
		var anchorId     = $(this).attr('href').substring(1),
			anchorPosTop = $('(h1, h2)[id="' + anchorId + '"]').offset().top;
		
		$('html, body').animate({scrollTop: anchorPosTop}, 'fast');
		
		return false;
	}
};