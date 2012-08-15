<?php
defined('SYS') or die('Core error: System path is not declared!');
defined('CONTROLLER') or die('Core error: Controller path is not declared!');
defined('MODEL') or die('Core error: Model path is not declared!');

// Класс ядра

/*	Использование
	
	Подключение ядра:
		require 'sys/core.php';
		
	Включение автоподгрузки классов:
		spl_autoload_register(array('core', 'auto_load'));
	
	P.S. Данный класс должен использоваться только в index.php
*/

class core {
	
	protected static $paths = array(SYS, CONTROLLER, MODEL);		// Массив с директориями классов
	
	/**
	 * Функция автоматической подгрузки необходимых файлов
	 *
	 * @param string $class Имя подключаемого класса (оно должно соответствовать имени файла, в котором находится класс)
	 */
	public static function auto_load($class) {
		
		foreach(core::$paths as $dir) {
			
			$path = str_replace('__', '/', strtolower($class));		// Двойное подчёркивание заменяется на слеш
			
			$file = $dir . $path . '.php';
			
			if(is_file($file)) {
				require $file;
				break;
			}
		}
	}
	
	/**
	 * Функция разбора адресной строки на части
	 *
	 * @param string $urn URN для обработки
	 * @return array
	 */
	public static function parse_urn($urn = null) {
		
		if(is_null($urn))
			$urn = $_SERVER['REQUEST_URI'];
			
		return preg_split('/\//', $urn, -1, PREG_SPLIT_NO_EMPTY);
	}
	
	private static $called = false;									// Флаг для определения была ли уже вызвана функция по текущему маршруту
	
	/**
	 * Функция обработки маршрутов, отправленных методами GET и POST
	 *
	 * @param string $type     Тип запроса [GET || POST]
	 * @param string $path     Путь, указанный в роуте
	 * @param string $callback Класс->Метод для вызова
	 * @param array  $asserts  Массив регулярных выражений для проверки {переменных}
	 * @return boolean
	 */
	public static function request($type, $path, $callback, $asserts = array()) {
		
		if(core::$called)											// Если маршрут был проведён
			return false;											// то все последующие роуты игнорируются
		
		if($_SERVER['REQUEST_METHOD'] != $type)						// Проверка на соответствие метода вызова
			return false;											// значит надо вызывать следующий маршрут в index.php
		
		$urn  = core::parse_urn();									// Текущий URN
		$path = core::parse_urn($path);								// Переданный для маршрутизации путь
		
		if(count($urn) != count($path))								// Если количество частей URN и пути разное
			return false;											// значит надо вызывать следующий маршрут в index.php
		
		$args = array();											// Объявление массива аргументов
		
		for($part = 0; $part < count($urn); $part++)
			if(preg_match('|^\{(.*)\}$|', $path[$part], $match))	// Если часть пути является {переменной}
				if(!isset($asserts[$match[1]]) || 					// Если для этой переменной не назначено регулярное выражение
					preg_match($asserts[$match[1]], $urn[$part])) {	// или если переменная проходит проверку регулярным выражением
					$args[$match[1]] = $urn[$part];					// Запись переменной в массив аргументов для дальнейшей передачи функции
					get::set_arg($match[1], $urn[$part]);			// Добавление пары ключ-значение в объект для работы с переменными
				}
				else {												// Иначе переменная не проходит проверку регулярным выражением
					get::unset_args();								// Нужно очистить объект переменных
					return false;									// и вызывать следующий маршрут в index.php
				}
			else													// иначе часть пути не является переменной
				if($urn[$part] != $path[$part]) {					// и если часть URN не совпадает с частью пути
					get::unset_args();								// Нужно очистить объект переменных
					return false;									// и вызывать следующий маршрут в index.php
				}
		
		$call = explode('->', $callback);							// Разбор callback на две части: 1) До стрелки и 2) После стрелки
		
		core::$called = true;										// Изменение флага для определения, что по текущему маршруту уже проведён роут
		
		if(method_exists($call[0], $call[1]))						// Если метод существует
			call_user_func_array(									// Вызов
				array($call[0], $call[1]),							// из класса $call[0] метода с именем $call[1]
				$args												// и параметрами из массива $args
			);
		else
			error::print_error(										// Иначе метод не существует
				'[' . $type . '] Route error: Function is undefined: '
				. $call[0] . '->' . $call[1]
			);
	}
	
	private static $default_404_options = array(					// Дефолтные параметры для ненайденной страницы
		'title'   => 'Страница не найдена',
		'header'  => 'Страница не найдена',
		'content' => '',
		'sysauto' => false
	);
	
	/**
	 * Функция возврата ошибки 404
	 *
	 * @param array $options Массив опций [title, header, content]
	 */
	public static function not_found($options = array()) {
		
		if(core::$called && $options['sysauto'])					// Если маршрут был проведён и функция вызывается автоматически с главной страницы после всех роутов
			return false;											// то страница найдена и ошибка 404 не нужна
		
		header('HTTP/1.1 404 Not Found');
		
		foreach(core::$default_404_options as $key => $val)			// Установка значений по умолчанию
			if(!isset($options[$key]))								// для незаданных опций
				$options[$key] = $val;
		
		$template = new Blitz(ROOT . '/view/blocks/html/view/404.tpl');
		
		echo $template->parse(array(
			'title'   => $options['title'],
			'header'  => $options['header'],
			'content' => $options['content']
		));
	}

	/**
	 * Функция сохранения флага режима разработчика в JS
	 * 
	 * @param boolean $dev Флаг режима разработчика
	 */
	public static function dev($dev = false) {

		if(
			isset($_SESSION['DEV']) && $_SESSION['DEV'] && !$dev || // Если режим разработчика был включен, а сейчас его выключили
			$dev													// или он просто включен
		) {

			file_put_contents(ROOT . '/view/includes/dev.js', 'window.DEV=' . (($dev) ? 'true;' : 'false;'));
			$ret = true;											// то надо вернуть true, чтобы собрать JS-файлы с новым значением
		}
		else														// Иначе режим разработчика выключен
			$ret = false;

		$_SESSION['DEV'] = $dev;									// Присваивание текущего значения флага режима разработчика

		return $ret;
	}
}

// Класс работы с GET-переменными

/*	Использование

	Получение значения GET-переменной:
		$value = get::$arg->key;
*/

class get {
	
	public static $arg;												// Объект, который используется из приложения для обращения к GET-переменным
	
	/**
	 * Функция добавления свойства для объекта $arg
	 *
	 * @param string $key Имя GET-переменной
	 * @param string $val Значение GET-переменной
	 */
	public static function set_arg($key, $val) {
		
		get::$arg->$key = $val;
	}
	
	/**
	 * Функция удаления всех свойств объекта $arg
	 *
	 */
	public static function unset_args() {
		
		if(count(get::$arg))										// Если объект аргументов содержит хотя бы одно значение
			foreach(get_object_vars(get::$arg) as $key => $val)
				get::$arg->$key = '';
	}
}

// Класс работы с базой данных

/* 	Использование
	
*/
class orm {
	
	public static $mysqli;											// Объект работы с MySQL
	
	private static $queries = array();								// Массив данных о выполняемых запросах
	private static $parameters = null;								// Массив параметров текущей операции
	private static $object;											// Текущий объект
	
	/**
	 * Конструктор для сохранения текущей операции
	 *
	 * @param string $operation Название текущей операции
	 */
	private function __construct($operation = null) {
		
		orm::$queries[count(orm::$queries)]->name = $operation;
		
		orm::$limit      = null;									// Обнуление дополнительных переменных перед каждым новым запросом
		orm::$order      = null;
		orm::$group      = null;
		orm::$subqueries = null;
		orm::$prefix     = null;
	}
	
	/**
	 * Функция подключения к MySQL
	 *
	 * @param string $host     Имя хоста
	 * @param string $login    Логин
	 * @param string $password Пароль
	 */
	public static function connect($host, $login, $password) {
		
		orm::$mysqli = new mysqli($host, $login, $password);
	}
	
	/**
	 * Функция выбора базы данных
	 *
	 * @param string $db Имя базы данных
	 */
	public static function db($db) {
		
		if(!orm::$mysqli->select_db($db))
			error::print_error('Selected database <b>' . $db . '</b> not found');
	}
	
	/**
	 * Функция преобразования значений для использования в SQL-запросе
	 *
	 * @param mixed $val Значение для преобразования
	 * @return mixed
	 */
	 private static function get_value($val) {
		
		if(strpos($val, 'func:') !== false) {					// Если в значении присутствует ключевое слово, указывающее на функцию
			
			$val = str_replace('func:', '', $val);				// Удаление ключевого слова из значения
			$val = str_replace(' ', '', $val);					// Удаление пробелов из значения
		}
		else
			$quote = (gettype($val) == 'string'					// Если у значения строковый тип
				&& !preg_match('/^\d+$/', $val)					// и это не число со строковым типом
				&& strtolower($val) != 'null') ? '\'' : '';		// и это не null, то надо добавить кавычки
		
		return $quote . $val . $quote;							// При необходимости возвращаемое значение обрамляется в апострофы
	 }
	
	/**
	 * Функция добавления записи в базу данных
	 *
	 * @param string $table  Имя таблицы
	 * @param array  $values Массив со значениями
	 * @return integer || boolean
	 */
	public static function insert($table, $values) {
		
		orm::$parameters = array($table, $values);
		
		new orm(__FUNCTION__);
		
		orm::set_debug(debug_backtrace());
		
		foreach($values as $key => $val) {
			
			$fields .= $key . ', ';
			$variables .=  orm::get_value($val) . ', ';
		}
		
		if(!orm::execute_query('insert into ' . $table . '(' . substr($fields, 0, -2) . ') values (' . substr($variables, 0, -2) . ')'))
			return false;											// Запрос не выполнен и возвращается отрицательный результат
		
		return orm::$mysqli->insert_id;								// Возвращается последний добавленный идентификатор
	}
	
	/**
	 * Функция обновления записи в базе данных
	 *
	 * @param string $table  Имя таблицы
	 * @param array  $values Массив со значениями
	 * @return object
	 */
	public static function update($table, $values) {
		
		orm::$parameters = array($table, $values);
		
		orm::$object = new orm(__FUNCTION__);
		orm::set_debug(debug_backtrace());
		
		return orm::$object;
	}
	
	/**
	 * Функция обработки данных перед отправкой на выполнение запроса на обновление записи
	 *
	 * @param string $table  Имя таблицы
	 * @param array  $values Массив со значениями
	 * @return boolean
	 */
	private static function update_query($table, $values) {
		
		foreach($values as $key => $val)
			$variables .= $key . ' = ' . orm::get_value($val) . ', ';
		
		return orm::execute_query('update ' . $table . ' set ' . substr($variables, 0, -2) . orm::$where);
	}
	
	/**
	 * Функция удаления записи из базы данных
	 *
	 * @param string $table Имя таблицы
	 * @return object
	 */
	public static function delete($table) {
		
		orm::$parameters = array($table);
		orm::$object = new orm(__FUNCTION__);
		orm::set_debug(debug_backtrace());
		
		return orm::$object;
	}
	
	/**
	 * Функция обработки данных перед отправкой на выполнение запроса на удаление записи
	 *
	 * @param string $table  Имя таблицы
	 * @return boolean
	 */
	private static function delete_query($table) {
		
		return orm::execute_query('delete from ' . $table . orm::$where);
	}
	
	/**
	 * Функция выборки записей из базы данных
	 *
	 * @param string $table Имя таблицы
	 * @return object
	 */
	public static function select($table) {
		
		orm::$parameters = array($table);
		orm::$object = new orm(__FUNCTION__);
		orm::set_debug(debug_backtrace());
		
		return orm::$object;
	}
	
	private static $int_array = array(								// Массив типов данных базы данных, которые необходимо перевести в integer
		'tinyint' => 1, 'smallint' => 2, 'integer' => 3, 
		'bigint' => 8, 'mediumint' => 9, 'year' => 13
	);
	
	private static $float_array = array(							// Массив типов данных базы данных, которые необходимо перевести в float
		'float' => 4, 'double' => 5
	);
	
	/**
	 * Функция обработки данных перед отправкой на выполнение запроса на выборку
	 *
	 * @param string $table  Имя таблицы
	 * @return array || boolean
	 */
	private static function select_query($table) {
		
		$result = orm::execute_query('select *' . orm::$subqueries . ' from ' . $table . orm::$where . orm::$group . orm::$order . orm::$limit);
		
		if(!$result)
			return false;
		
		else {
			
			$result_array = array();
			
			while($current_row = $result->fetch_object()) {			// Цикл по строкам результатов выборки
				
				foreach($result->fetch_fields() as $val) {			// Цикл по полям текущей строки
					
					$name = $val->name;								// Имя текущего поля
					
					if(!is_null(orm::$prefix)) {					// Если требуется добавить префикс
						
						$key = substr($name, -3);					// Последние три символа названия поля
						
						if($key != '_id' && $key != '_fk') {		// Если текущее поле не является первичным или внешним ключом
							
							$prefix_name = orm::$prefix . $name;				// Формирование нового имени для поля
							$current_row->$prefix_name = $current_row->$name;	// Присваивание значения из старого свойства объекта свойству с новым именем
							unset($current_row->$name);							// Удаление свойства со старым именем
							$name = $prefix_name;								// Замена основного имени на новое с префиксом
						}
					}
					
					if(in_array($val->type, orm::$int_array))					// Если тип данных текущего поля является числовым и целым
						$current_row->$name = intval($current_row->$name);		// то это поле надо перевести в целое число
					
					else if(in_array($val->type, orm::$float_array))			// Если тип данных текущего поля является числовым и дробным
						$current_row->$name = floatval($current_row->$name);	// то это поле надо перевести в дробное число
				}
				
				array_push($result_array, $current_row);			// Запись строки в результирующий массив
			}
			
			if(gettype(orm::$where) == 'integer' || 				// Если в качестве условия было передано число
				preg_match('/^\d+$/', orm::$where))					// или число в виде строки (т.е. была запрошена одна строка)
				return $result_array[0];							// то нужно вернуть именно её
			else
				return $result_array;								// иначе массив записей
		}
	}
	
	private static $where;											// Переменная, хранящая переданные условия
	
	/**
	 * Функция условия
	 *
	 * @param string || integer $where Текст условия
	 * @return mixed
	 */
	public function where($where) {
		
		if(!$where)																// Если аргумент отсутствует
			error::print_error('Missing argument for <b>where</b> in <b>' . orm::$queries[count(orm::$queries) - 1]->name . '</b> query');
		
		else if(gettype($where) == 'integer' || preg_match('/^\d+$/', $where))	// иначе если аргумент имеется и это целое число или это строка, являющаяся числом
			orm::$where = ' where ' . orm::$parameters[0] . '_id = ' . $where;
		
		else if(gettype($where) == 'string')									// иначе если аргумент имеется и это строка
			orm::$where = ($where == 'all') ? '' : ' where ' . $where;			// Если запрос выполняется для всех записей, то условие не нужно
		
		else																	// Иначе аргумент имеется, но у него неверный тип данных
			error::print_error('Wrong argument for <b>where</b> in <b>' . orm::$queries[count(orm::$queries) - 1]->name . '</b> query');
		
		return call_user_func_array(
			array('orm', orm::$queries[count(orm::$queries) - 1]->name . '_query'),
			orm::$parameters
		);
	}
	
	/**
	 * Функция присоединения таблиц
	 *
	 * @param array  $table Массив массивов выборок
	 * @return array
	 */
	public static function inner($tables) {
		
		$index = 0;												// Итератор для главного цикла
		
		foreach($tables as $prefix => $tab) {					// Цикл по выборкам
			
			if(gettype($tab) == 'object')						// Если таблица является объектом
				$table[0] = $tab;								// то этот объект надо сделать первым элементом массива
			else if(gettype($tab) == 'array')					// Иначе если это массив
				$table = $tab;									// и таблицу надо просто переприсовить
			
			if($index > 0) {									// Если сейчас не первая таблица
				
				if(gettype($tab) == 'string')							// Если текущая таблица - строка
					$table = orm::select($tab)->limit(1)->where('all');	// надо сделать выборку одной строки, чтобы затем выявить поля-ключи
				
				$right_table_fk = array();								// Массив внешних ключей правой таблицы
				$right_table_id = array();								// Массив первичных ключей правой таблицы
				
				if(isset($table[0]))									// Если у текущей таблицы есть хотя бы одна строка выборки
					foreach($table[0] as $field => $value)				// Цикл по первой строке текущей таблицы
						if(substr($field, -3) == '_fk') {				// Если текущее поле является внешним ключём
							$fk_name = explode('_fk', $field);
							array_push($right_table_fk, $fk_name[0]);	// то его надо добавить в массив внешних ключей
						}
						else if(substr($field, -3) == '_id') {			// Иначе если текущее поле является первичным ключём
							$id_name = explode('_id', $field);
							array_push($right_table_id, $id_name[0]);	// то его надо добавить в массив первичных ключей
						}
				
				$tables_key = null;
				
				foreach($right_table_fk as $fk)						// Цикл по внешним ключам правой таблицы
					foreach($left_table_id as $id)					// Цикл по первичным ключам левой таблицы
						if($fk == $id) {							// Если ключи совпадают
							
							$relation = 'left';						// Связь направлена влево
							$tables_key = $fk;						// то по этому ключу будут объединяться строки
						}
				
				if(is_null($tables_key))							// Если соответствие ключей не было найдено
					foreach($right_table_id as $id)					// Цикл по первичным ключам правой таблицы
						foreach($left_table_fk as $fk)				// Цикл по внешним ключам левой таблицы
							if($fk == $id) {						// Если ключи совпадают
								
								$relation = 'right';				// Связь направлена вправо
								$tables_key = $fk;					// то по этому ключу будут объединяться строки
							}
				
				if(!is_null($tables_key)) {							// Если соответствие ключей найдено
					
					$tables_key_id = $tables_key . '_id';							// Имя поля первичного ключа
					$tables_key_fk = $tables_key . '_fk';							// Имя поля внешнего ключа
					$result = array();												// Результирующий массив
					
					if(gettype($tab) == 'string') {									// Если текущая таблица - строка
						
						$table = array();											// Массив формируемой таблицы
						$exist = array();											// Массив первичных ключей, которые уже добавлены
						
						foreach($left_table as $left_row) {							// Цикл по строкам левой таблицы
							
							if($relation == 'left')
								$where = $tables_key_fk . ' = ' . $left_row->$tables_key_id;
							else if($relation == 'right')
								$where = $tables_key_id . ' = ' . $left_row->$tables_key_fk;
							
							$right_rows = orm::select($tab)->where($where);			// Запрос к текущей таблице в соответствии с найденными ключами
							
							foreach($right_rows as $row) {							// Цикл по полученным в результате запроса строкам
								
								if($relation == 'right') {							// Если связь направлена вправо
									
									if(!in_array($row->$tables_key_id, $exist)) {	// Если в массиве ещё нет текущего первичного ключа
										array_push($exist, $row->$tables_key_id);	// Добавление нового ключа в массив первичных ключей
										array_push($table, $row);					// Добавление полученных строк в текущую таблицу
									}
								}
								else if($relation == 'left') {						// Иначе если связь направлена влево
									
									$right_table_key = $right_table_id[0] . '_id';	// У правой таблицы, переданной в виде строки есть только один первичный ключ
									
									if(!in_array($row->$right_table_key, $exist)) {	// Если в массиве ещё нет текущего первичного ключа
										array_push($exist, $row->$right_table_key);	// Добавление нового ключа в массив первичных ключей
										array_push($table, $row);					// Добавление полученных строк в текущую таблицу
									}
								}
							}
						}
					}
					
					if($relation == 'right')										// Если связь направлена вправо
						list($left_table, $table) = array($table, $left_table);		// то нужно поменять местами таблицы для их дальнейшего объединения
					
					foreach($left_table as $left_row => $left_row_values) {			// Цикл по строкам левой таблицы
						
						foreach($table as $row => $row_values) {					// Цикл по строкам правой таблицы
							
							if($left_row_values->$tables_key_id == $row_values->$tables_key_fk) {	// Если значения ключей совпадают
								
								// array_push($result, (object) array_merge((array) $left_row_values, (array) $row_values)); // Слияние объектов
								
								if(gettype($prefix) == 'string' && $relation == 'right') {			// Если передан префикс и связь направлена вправо
									
									$tmp = new stdClass;
									
									foreach($left_row_values as $tmp_field => $tmp_value) {			// Цикл по полям текущей строки левой таблицы
										
										if(substr($tmp_field, -3) != '_fk' && substr($tmp_field, -3) != '_id') {	// Если текущее поле не является ключом
											
											$tmp_new_field = $prefix . $tmp_field;					// Формирование нового имени для поля с учётом префикса
											$tmp->$tmp_new_field = $tmp_value;						// Добавление нового свойства с прежним значением
										}
										else														// Иначе текущее поле является ключом
											$tmp->$tmp_field = $tmp_value;							// и его нужно просто переприсвоить
									}
								}
								else																// Иначе префикс не передан или связь направлена влево
									$tmp = clone $left_row_values;
								
								foreach($row_values as $field => $value) {					// Цикл по полям правой таблицы
									
									if(substr($field, -3) != '_fk' && substr($field, -3) != '_id') {
									
										if(gettype($prefix) == 'string' && $relation == 'left')	// Если указан префикс и связь направлена влево
											$field = $prefix . $field;
										else if(property_exists($tmp, $field)) {				// Иначе префикс не указан и если поле с таким названием уже есть
											
											// if($relation == 'right') {						// Если связь направлена вправо
												
												$left_field = $tables_key . '_' . $field;	// Новое название для поля
												$tmp->$left_field = $tmp->$field;			// Присваивание значения полю с новым названием
											// }
											// else if($relation == 'left')					// Иначе если связь направлена влево
												// $field = $tables_key . '_' . $field;		// Нужно просто задать новое название для поля
										}
									}
									
									$tmp->$field = $value;									// Присваивание объекту левой таблицы значений свойств правой таблицы
								}
								
								array_push($result, $tmp);
								
								// Надо не очищать $result, а добавлять в него строки, тогда не придётся
								// передавать его значение в $left_table
								// array_splice($result, $left_row, 0, $tmp);
							}
						}
					}
				}
				else if(count($left_table) > 0 && count($table) > 0) {						// Иначе связи не обнаружены и если у обоих таблиц есть хотя бы одна запись
					
					$debug_info = debug_backtrace();
					error::print_error('<b>inner</b> can\'t found conformity keys in <b>' . $debug_info[0]['file'] . '</b> on line <b>' . $debug_info[0]['line'] . '</b>');
				}
				
				$left_table = $result;								// Массив левой таблицы - это результат слияния таблиц
			}
			else {													// Иначе сейчас первая таблица
				
				if(gettype($tab) == 'string')						// Если переданная таблица - строка
					$table = orm::select($tab)->where('all');		// надо сделать выборку в ручную
				
				if(gettype($prefix) == 'string') {					// Если требуется добавить префикс
					
					$left_table = array();
					
					foreach($table as $row => $row_values) {			// Цикл по строкам первой таблицы
						
						$left_table_row = new stdClass;
						
						foreach($row_values as $field => $value) {		// Цикл по полям текущей строки первой таблицы
						
							if(substr($field, -3) != '_fk' && substr($field, -3) != '_id') {	// Если текущее поле не является ключом
								
								$new_field = $prefix . $field;			// Формирование нового имени для поля с учётом префикса
								$left_table_row->$new_field = $value;	// Добавление нового свойства с прежним значением
							}
							else										// Иначе текущее поле является ключом
								$left_table_row->$field = $value;		// и его нужно просто переприсвоить
						}
						
						array_push($left_table, $left_table_row);		// Добавление сформированной строки в новый массив первой таблицы
					}
				}
				else
					$left_table = $table;							// Иначе, если префикс не требуентся, массив левой таблицы - это первая таблица (слияний пока не было)
			}
			
			$left_table_id = array();								// Массив первичных ключей левой таблицы
			$left_table_fk = array();								// Массив внешних ключей левой таблицы
			
			if(isset($left_table[0]))								// Если у текущей таблицы есть хотя бы одна строка выборки
				foreach($left_table[0] as $field => $value) {		// Цикл по первой строке текущей таблицы

					if(substr($field, -3) == '_id') {				// Если текущее поле является первичным ключём
						
						$id_name = explode('_id', $field);
						array_push($left_table_id, $id_name[0]);	// то его надо добавить в массив первичных ключей
					}
					else if(substr($field, -3) == '_fk') {			// Иначе если текущее поле является внешним ключём
						
						$fk_name = explode('_fk', $field);
						array_push($left_table_fk, $fk_name[0]);	// то его надо добавить в массив внешних ключей
					}
				}
			
			$index++;
		}
		
		return $result;
	}
	
	private static $limit;											// Переменная, хранящая значение для оператора limit
	
	/**
	 * Функция добавления значения для оператора limit к запросу
	 *
	 * @param string $limit Значение оператора
	 */
	public function limit($limit) {
		
		orm::$limit = ' limit ' . $limit;
		
		return orm::$object;
	}
	
	private static $order;											// Переменная, хранящая значение для оператора order
	
	/**
	 * Функция добавления значения для оператора order к запросу
	 *
	 * @param string $order Значение оператора
	 */
	public function order($order) {
		
		orm::$order = ' order by ' . $order;
		
		return orm::$object;
	}
	
	private static $group;											// Переменная, хранящая значение для оператора group
	
	/**
	 * Функция добавления значения для оператора group к запросу
	 *
	 * @param string $group Значение оператора
	 */
	public function group($group) {
		
		orm::$group = ' group by ' . $group;
		
		return orm::$object;
	}
	
	private static $subqueries;										// Переменная, хранящая подзапросы
	
	/**
	 * Функция добавления подзапросов
	 *
	 * @param array $subqueries Массив с текстами подзапросов
	 */
	public function sub($subqueries) {
		
		foreach($subqueries as $val => $key)
			orm::$subqueries .= ', (' . $val . ') as ' . $key;
		
		return orm::$object;
	}
	
	private static $prefix;											// Переменная, хранящая значение префикса
	
	/**
	 * Функция добавления значения префикса для полей таблицы
	 *
	 * @param string $prefix Значение для префикса
	 */
	public function prefix($prefix) {
		
		orm::$prefix = $prefix;
		
		return orm::$object;
	}
	
	public static $print = false;									// Печать всех запросов
	
	/**
	 * Функция непосредственного выполнения запроса
	 *
	 * @param string $query SQL-запрос
	 * @return boolean
	 */
	private static function execute_query($query) {
		
		orm::$queries[count(orm::$queries) - 1]->query = $query;	// Запись в массив данных текста текущего запроса
		
		if(orm::$print)												// Если нужно напечатать текст запроса
			echo '<br>' . $query . '<br>';
		
		$start = microtime(true);									// Время начала выполнения запроса
		$result = orm::$mysqli->query($query);						// Выполнение самого запроса
		orm::$queries[count(orm::$queries) - 1]						// Запись в массив данных
			->duration = microtime(true) - $start;					// длительности выполнения запроса
		
		if(!$result) {
			
			orm::$queries[count(orm::$queries) - 1]->result = '<b>error:</b> ' . orm::$mysqli->error;
			return false;
		}
		else if(orm::$queries[count(orm::$queries) - 1]->name == 'select') {
			
			orm::$queries[count(orm::$queries) - 1]->result = 'complete: ' . $result->num_rows . ' rows';
			return $result;
		}
		else {
			
			orm::$queries[count(orm::$queries) - 1]->result = 'complete';
			return true;
		}
	}
	
	/**
	 * Функция добавления информации по выполняемым запросам
	 *
	 */
	private static function set_debug($backtrace) {
		
		orm::$queries[count(orm::$queries) - 1]->file = $backtrace[0]['file'];
		orm::$queries[count(orm::$queries) - 1]->line = $backtrace[0]['line'];
	}
	
	/**
	 * Функция вывода информации по отработанным запросам
	 *
	 */
	public static function debug() {
		
		echo "<pre><b>Queries debuger:</b>\n\n";
		
		foreach(orm::$queries as $key => $val) {
			
			echo $key + 1 . " -> " . $val->name . " [\n"
				. "\t"   . "file -> "     . $val->file
				. "\n\t" . "line -> "     . $val->line
				. "\n\t" . "query -> "    . $val->query
				. "\n\t" . "duration -> " . $val->duration
				. "\n\t" . "result -> "   . $val->result
				. "\n]\n\n";
			
			$duration_sum += $val->duration;
		}
		
		echo "total [\n"
			. "\t" .   "count -> "    . count(orm::$queries)
			. "\n\t" . "duration -> " . $duration_sum
			. "\n]</pre>";
	}
	
	/**
	 * Функция вывода массива выборки в удобочитаемом виде
	 *
	 * @param array || object $query
	 */
	public static function print_query($query) {
		
		if(gettype($query) == 'object')								// Если параметр является объектом (одна строка в результате выборки)
			$table[0] = $query;										// то надо добавить его в массив
		else														// Иначе это массив
			$table = $query;										// и его надо просто переприсвоить
		
		echo "<pre><b>Query result: </b>";
		
		if(count($table) == 0)										// Если выборка пуста
			echo "empty\n\n";
		else {														// Если есть результаты выборки
			
			echo "\n\n";
			
			foreach($table as $num => $row) {						// Цикл по строкам результата выборки
				
				echo $num + 1 . " -> [\n";
				
				foreach($row as $key => $val)						// Цикл по полям текущей строки
					echo "\t" . $key . " => " . $val . "\n";
				
				echo "]\n\n";
			}
		}
		
		echo "</pre>";
	}
}

// Класс обработки ошибок

/*	Использование
	
	Отключение отображения ошибок интерпретатора:
		error_reporting(0);
	
	Указание метода, которые будет вызван по окончании выполнения всего скрипта:
		register_shutdown_function(array('error', 'get_error'));
	
	 Вывод ошибки системы:
		error::print_error('Error text');
*/
class error {
	
	protected static $sys_classes = array(							// Определение классов системы, имена которых нельзя использовать в приложении
		'core', 'get', 'orm', 'error', 'message'
	);
	
	/**
	 * Функция обработки ошибок интерпретатора
	 * 
	 */
	public static function get_error() {
		
		$info = error_get_last();									// Получение массива с информацией о последней ошибке в таком формате: Array([type] => 1 [message] => Message text [file] => Path to file [line] => 1 ) 
		
		switch($info['type']) {
			
			case 1:													// Если ошибка является фатальной
				
				if(stripos($info['message'], 
					'Call to undefined method') === 0) {			// Если это ошибка вызова неизвестного метода
					
					if(preg_match('|Call to undefined method (.*)::|', $info['message'], $match)) {
						
						foreach(error::$sys_classes as $class)
							if($class == $match[1]) {				// Если имя вызываемого класса совпадает хотя бы с одним из системных классов
								
								echo error::print_error('Called class-name (<b>' . $match[1] . '</b>) is used in system. Other reserved system class-name: ');
								
								foreach(error::$sys_classes as $class)
									echo '<b>' . $class . '</b>; ';
								
								break;
							}
					}
				}
				
				break;
		}
	}
	
	/*
	 * Функция печати ошибок системы
	 *
	 * @param string $text Текст ошибки
	 */
	public static function print_error($text) {
		
		die('<br><b>Framework error</b>: ' . $text);
	}
}

// Класс вывода сообщений

/*	Использование:
	
	Вывод сообщения системы:
		message::print_message('Message text');
*/

class message {
	
	/*
	 * Функция печати сообщений системы
	 *
	 * @param string $text Текст сообщения
	 */
	public static function print_message($text) {
		
		echo '<br><b>Framework message</b>: ' . $text;
	}
}