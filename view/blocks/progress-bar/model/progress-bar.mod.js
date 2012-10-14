var progressBarMod = {

	init: function() {																		// Инициализация объекта

		$('.button_change-percent').click(progressBarMod.buttonChange);
		$('.button_change-percent_interval').click(progressBarMod.buttonChangeInterval);
	},

	buttonChange: function() {																// Единичное изменение прогресс-бара
		
		var percent = Number($(this).text().slice(0, -1));									// Чистый процент

		progressBarMod.change(percent);
	},

	buttonChangeInterval: function() {														// Множественное изменение прогресс-бара

		var percent  = $(this).text().slice(0, -1),											// Чистый интервал
			interval = percent.split('-'),
			start    = Number(interval[0]),													// Начальное значение
			stop     = Number(interval[1]);													// Конечное значение
		
		for(var p = start; p <= stop; p++)													// Цикл от начального к конечному значению
			progressBarMod.change(p);
	},

	change: function(percent) {																// Изменение прогресс-бара
		
		if(percent < 0 || percent > 100)													// Процент не может быть меньше нуля и больше ста
			return;

		$('.progress-bar__progress')
			.animate({																		// Изменение ширины прогресса
				'width': percent + '%'
			}, function() {

				$('.progress-bar__percent').text(percent + '%');							// Изменение текста
			});
	}
};