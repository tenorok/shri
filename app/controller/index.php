<?php

// Контроллер работы с главной страницей

class index {
	
	/**
	 * Отображение главной страницы
	 *
	 */
	public static function page() {

		$html = new Blitz(BLOCKS . 'html/view/html.tpl');

		$files = 
			file_get_contents('view/includes/libs.tpl') .
			((DEV) ? file_get_contents('view/includes/developer.tpl') : '') .
			file_get_contents('view/includes/require.tpl');

		$menu    = new Blitz(BLOCKS . 'menu/view/menu.tpl');
		$content = new Blitz(BLOCKS . 'content/view/content.tpl');
		$general = new Blitz(BLOCKS . 'info/view/general.tpl');

		$info = 
			$general->parse();

		$body =
			$menu   ->parse() .
			$content->parse(array(
				'content' => $info
			));

		echo $html->parse(array(
			'title' => 'Артём Курбатов - Тестовое задание для Школы разработки интерфейсов Яндекса',
			'files' => $files,
			'body'  => $body
		));
	}
}