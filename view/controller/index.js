var index = {

	init: function(anchor) {

		$('.menu__link_sub_yes').unbind('click').click(index.toggleSubMenu);

		if(anchor !== undefined) {

			var menu = anchor.split('-')[0];
			var link = $('.menu__link_sub_yes[href="#/' + menu + '"]');

			if($(link).next('.menu__submenu').is(':hidden'))
				$(link).trigger('click');
		}
	},

	toggleSubMenu: function() {

		var subMenu = $(this).next('.menu__submenu');
		var arrow   = $(this).children('.menu__link-arrow');

		if($(subMenu).is(':hidden')) {

			$(subMenu).slideDown('fast');
			$(arrow).html('&#9650;');
		}
		else {

			$(subMenu).slideUp('fast');
			$(arrow).html('&#9660;');
		}

		return false;
	}
};