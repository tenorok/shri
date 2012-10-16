<h3>Прогресс-бар</h3>
<div class="progress-bar">
	<div class="progress-bar__line">
		<div class="progress-bar__progress" style="width: 10%"></div>
		<span class="progress-bar__percent">10%</span>
	</div>
</div>
<p>Состояние прогресс-бара изменяется путём вызова метода <code class="code">progressBarMod.change(percent)</code>, где percent - это процент (число).</p>
<p>Примеры единичного вызова:</p>
<button class="button button_change-percent">20%</button>
<button class="button button_change-percent">70%</button>
<button class="button button_change-percent">100%</button>
<p>Примеры ряда последовательных вызовов:</p>
<button class="button button_change-percent_interval">0-30%</button>
<button class="button button_change-percent_interval">30-70%</button>
<button class="button button_change-percent_interval">70-146%</button>

<p><a href="https://github.com/tenorok/shri/blob/master/view/blocks/progress-bar/model/progress-bar.mod.js">Объект</a>, отвечающий за работу прогресс-бара:</p>
<div class="info__copy-code"><pre><code class="javascript">var progressBarMod = {

	init: function() {

		$('.button_change-percent').click(progressBarMod.buttonChange);
		$('.button_change-percent_interval').click(progressBarMod.buttonChangeInterval);
	},

	buttonChange: function() {
		
		var percent = Number($(this).text().slice(0, -1));

		progressBarMod.change(percent);
	},

	buttonChangeInterval: function() {

		var percent  = $(this).text().slice(0, -1),
			interval = percent.split('-'),
			start    = Number(interval[0]),
			stop     = Number(interval[1]);
		
		for(var p = start; p <= stop; p++)
			progressBarMod.change(p);
	},

	change: function(percent) {
		
		if(percent < 0 || percent > 100)
			return;

		$('.progress-bar__progress')
			.animate({
				'width': percent + '%'
			}, function() {

				$('.progress-bar__percent').text(percent + '%');
			});
	}
};</code></pre></div>