<?php

// Контроллер работы с главной страницей

class index {
	
	/**
	 * Отображение главной страницы
	 *
	 */
	public static function page() {

		index::genPage('main');
	}

	/**
	 * Версия для печати
	 *
	 */
	public static function printPage() {

		index::genPage('print');
	}

	/**
	 * Формирование страницы
	 *
	 */
	private static function genPage($mode) {

		$files = 
			                      file_get_contents('view/includes/libs.tpl') .
			             ((DEV) ? file_get_contents('view/includes/developer.tpl') : '') .
			                      file_get_contents('view/includes/require.tpl') .
			(($mode == 'print') ? file_get_contents('view/includes/print.tpl') : '');

		$html       = new Blitz(BLOCKS . 'html/view/html.tpl');
		$menu       = new Blitz(BLOCKS . 'menu/view/menu.tpl');
		$content    = new Blitz(BLOCKS . 'content/view/content.tpl');
		$general    = new Blitz(BLOCKS . 'info/view/general.tpl');
		$experience = new Blitz(BLOCKS . 'info/view/experience.tpl');
		$technical  = new Blitz(BLOCKS . 'info/view/technical.tpl');
		
		$frontender = new Blitz(BLOCKS . 'info/view/frontender.tpl');
		$ratingCtrl = new Blitz(BLOCKS . 'rating-control/view/rating-control.tpl');

		$other      = new Blitz(BLOCKS . 'info/view/other.tpl');

		$info = 
			$general   ->parse() .
			$experience->parse() .
			$technical ->parse() .
			$frontender->parse(array(
				'rating-control' => $ratingCtrl->parse()
			)) .
			$other     ->parse();

		$body =
			(($mode != 'print') ? $menu->parse() : '') .
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