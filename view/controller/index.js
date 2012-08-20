var index = {

	init: function(anchor) {

		menuMod.init(anchor);			// Инициализация меню

		hljs.initHighlightingOnLoad();	// Подсветка синтаксиса

		infoMod.copyClipboardInit();	// Включение кнопок копирования кода
	}
};