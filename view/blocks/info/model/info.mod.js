var infoMod = {

	copyClipboardInit: function() {

		ZeroClipboard.setMoviePath('/assets/flash/ZeroClipboard.swf');

		var clip       = {},
			button     = '<button id="{id}" class="info__copy">копировать</button>',
			copiedtext = 'скопировано';

		$('.info__copy-code').each(function(i) {

			var id  = 'pre_' + i,
				ids = '#' + id;
			
			$(this).prepend(button.replace('{id}', id));

			clip[id] = new ZeroClipboard.Client();

			clip[id].setText($(this).find('code').html());
			clip[id].glue(id);

			$(window).bind('load resize', function() {
				clip[id].reposition();
			});
			
			clip[id].addEventListener('onComplete', function() {

				var buttonText = $(ids).text();

				$(ids)
					.text(copiedtext)
					.addClass('info__copy_state_copied');

				setTimeout(function() {
					
					$(ids)
						.text(buttonText)
						.removeClass('info__copy_state_copied');
				}, 1000);
			});
		});
	}
};