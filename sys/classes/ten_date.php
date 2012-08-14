<?php

// Класс работы с датой

/*	Использование

	Преобразование даты в привычный вид:
		Без времени:
			$date = ten_date::get_date('2011-04-28 18:21:02');
		Со временем:
			$date_time = ten_date::get_date('2011-04-28 18:21:02', true);
*/

class ten_date {
	
	protected static $month = array(									// Месяца в родительном падеже
		'01'=>'января','02'=>'февраля','03'=>'марта','04'=>'апреля',
		'05'=>'мая','06'=>'июня','07'=>'июля','08'=>'августа',
		'09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря'
	);
	
	public static $now = 300;											// Время в секундах для написания "сейчас"
	
	/**
	 * Функция преобразования даты в привычный вид
	 *
	 * @param  datetime $date Дата для преобразования
	 * @param  boolean $time Необходимость добавления времени к дате
	 * @return string
	 */
	public static function get_date($date, $time = false)
	{
		list($year, $month, $daytime) = explode('-', $date);
		
		if(substr($daytime, 0, 2) == date('d') && $year . '-' . $month == date('Y-m'))										// Если дата сегодняшняя
			$ret_date = 'сегодня';
		
		else if(((substr($daytime, 0, 1) == '0') ? substr($daytime, 1, 1) : substr($daytime, 0, 2)) == (date('j') - 1) && 	// Если дата вчерашняя
				$year . '-' . $month == date('Y-m'))
				$ret_date = 'вчера';
		
		else $ret_date = ((substr($daytime, 0, 1) == '0') ? substr($daytime, 1, 1) : substr($daytime, 0, 2)) . ' ' . 		// Если дата позавчерашняя и до этого
						 ten_date::$month[$month] . ' ' . 
						 (($year == date('Y')) ? '' : $year);																// Если год текущий, то его не надо печатать
		
		if($ret_date == 'сегодня' && strtotime('now') - strtotime($date) < ten_date::$now 									// Если дата сегодняшняя и время не далее, чем $now секунд назад
								  && strtotime('now') - strtotime($date) > 0)												// и разница между текущим временем и переданным положительна
			return 'сейчас';
		else																												// Иначе, либо дата не сегодняшняя, либо разница во времени больше, чем $now секунд
			return ((strtotime('now') < strtotime($date)) ? 'будет ' : '') . 												// Если надо печатать "будет"
				$ret_date . (($time == true) ? ' в ' . substr($daytime, 3, 5) : '');										// Если надо печатать время
	}
}
