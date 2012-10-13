var index = {

	init: function(anchor) {

		menuMod.init(anchor);			// Инициализация меню

		hljs.initHighlightingOnLoad();	// Подсветка синтаксиса

		infoMod.copyClipboardInit();	// Включение кнопок копирования кода

		contentMod.init();				// Версия для печати

		ratingCtrlMod.init();			// Рейтинг-контрол
	},

	initPrint: function() {
		hljs.initHighlightingOnLoad();	// Подсветка синтаксиса
	}
};