<h3>Контрол оценки и показа рейтинга</h3>
<ul class="rating-control">
	<li class="rating-control__item">
		Рейтинг: <span class="rating-control__rating">16</span>
	</li>
	<li class="rating-control__item">
		<a href="#" class="rating-control__link">1</a>
	</li>
	<li class="rating-control__item">
		<a href="#" class="rating-control__link">2</a>
	</li>
	<li class="rating-control__item">
		<a href="#" class="rating-control__link">3</a>
	</li>
	<li class="rating-control__item">
		<a href="#" class="rating-control__link">4</a>
	</li>
	<li class="rating-control__item">
		<a href="#" class="rating-control__link">5</a>
	</li>
</ul>
<p>JS-объект, отвечающий за работу контрола можно найти здесь: /view/blocks/rating-control/model/rating-control.mod.js</p>
<p>Или ниже:</p>
<div class="info__copy-code"><pre><code class="javascript">var ratingCtrlMod = {

	itemActiveClass: 'rating-control__item_active',

	init: function() {

		$('.rating-control__item').hover(ratingCtrlMod.over, ratingCtrlMod.out);

		$('.rating-control__link').click(ratingCtrlMod.choice);
	},

	over: function() {
		
		$(this)
			.addClass(ratingCtrlMod.itemActiveClass)
			.prevAll()
			.addClass(ratingCtrlMod.itemActiveClass);
	},

	out: function() {
		
		$(this)
			.removeClass(ratingCtrlMod.itemActiveClass)
			.prevAll()
			.removeClass(ratingCtrlMod.itemActiveClass);
	},

	choice: function() {

		$('.rating-control__item').unbind('hover');
		
		$('.rating-control__link')
			.unbind('click')
			.bind('click', function() { return false; });

		var currentRating = Number($('.rating-control__rating').text()),
			choiceRating  = Number($(this).text());

		$('.rating-control__rating').text(currentRating + choiceRating);

		return false;
	}
};</code></pre></div>