var ratingCtrlMod = {

	itemActiveClass: 'rating-control__item_active',										// Модификатор активной звёздочки

	init: function() {																	// Инициализация объекта

		$('.rating-control__item').hover(ratingCtrlMod.over, ratingCtrlMod.out);

		$('.rating-control__link').click(ratingCtrlMod.choice);
	},

	over: function() {																	// Курсор наведён на звёздочку
		
		$(this)
			.addClass(ratingCtrlMod.itemActiveClass)									// Нужно добавить ей
			.prevAll()
			.addClass(ratingCtrlMod.itemActiveClass);									// и всем предыдущим модификатор активности
	},

	out: function() {																	// Курсор убран со звёздочки
		
		$(this)
			.removeClass(ratingCtrlMod.itemActiveClass)									// Нужно убрать у неё
			.prevAll()
			.removeClass(ratingCtrlMod.itemActiveClass);								// и у всех предыдущих модификатор активности
	},

	choice: function() {																// Выставление рейтинга

		$('.rating-control__item').unbind('hover');										// Отмена события наведения курсора
		
		$('.rating-control__link')
			.unbind('click')															// Отмена события клика
			.bind('click', function() { return false; });								// Задание новой функции обработки клика для отключения перехода по ссылке

		var currentRating = Number($('.rating-control__rating').text()),				// Текущий рейтинг
			choiceRating  = Number($(this).text());										// Выбранный рейтинг

		$('.rating-control__rating').text(currentRating + choiceRating);				// Отображение нового рейтинга

		return false;																	// Отмена перехода по ссылке
	}
};