<?php

// Контроллер работы с главной страницей

class index {
	
	/**
	 * Отображение главной страницы
	 *
	 */
	public static function page() {

		$html = new Blitz(ROOT . '/view/blocks/html/view/html.tpl');

		$files = 
			file_get_contents('view/includes/libs.tpl') .
			((DEV) ? file_get_contents('view/includes/developer.tpl') : '') .
			file_get_contents('view/includes/require.tpl');

		echo $html->parse(array(
			'title' => 'Заголовок',
			'files' => $files,
			'body'  => 'Контент'
		));
	}
}