var contentMod = {

	init: function() {

		$('.content__link_type_print').click(contentMod.openPrintVer);
	},

	openPrintVer: function() {

		var url = $(this).attr('href');
		
		window.open(url, 'Версия для печати', 'left=20px, top=0, width=700px, scrollbars=yes');

		return false;
	}
};