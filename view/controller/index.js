var index = {

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

			if($(link).next('.menu__submenu').is(':hidden'))
				$(link).trigger('click');
		}

		hljs.initHighlightingOnLoad();

		infoMod.copyClipboardInit();
	}
};