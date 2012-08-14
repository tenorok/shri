<?php

// Version 1.1.2
// From 01.08.2012

// Класс работы с файлами

/*	Использование
	
	Сохранение массива в файл:
		ten_file::save_arr('/conf/settings.conf', array(
			'key_0' => 'val_0',
			'key_1' => 'val_1'
		));
		
	Чтение массива из файла:
		$content = ten_file::read_arr('/conf/settings.conf');
	
	Загрузка изображения:
		Простая загрузка одного изображения:
			<input type="file" name="image">
			$result = ten_file::upload_image($_FILES['image']);
			В случае успешной загрузки, в $result возвращается абсолютный путь и имя файла, 
			например: assets/images/directory_1/directory_2/name.jpg
		
		Простая загрузка массива изображений:
			<input type="file" name="images[]">
			$result = ten_file::upload_image($_FILES['images']);
			В случае успешной загрузки, в $result возвращается массив абсолютных путей и имена файлов, 
			например: Array (
				[0] => assets/images/directory_1/directory_2/name_1.jpg
				[1] => assets/images/directory_1/directory_2/name_2.jpg
				...
			)
		
		Загрузка с миниатюрами:
			$result = Array (
				[originals] => Array (
					[0] => assets/images/directory_1/directory_2/name_1.jpg
					...
				) [miniatures] => Array (
					[0] => assets/images/directory_1/directory_2/mini_name_1.jpg
					...
				)
			) 
		
		Возможные ошибки:
			if($result)
				// Загрузка прошла успешно
			else if($result == -1)
				// Ошибка: Неверный тип изображения
			else if($result == -2)
				// Ошибка: Слишком большой вес изображения
			else if($result == -3)
				// Ошибка: Невозможно прочесть информацию об изображении (скорее всего это не изображение)
			else if($result == -4)
				// Ошибка: Невозможно найти конечную директорию и не удалось её создать
			else
				// Неизвестная ошибка
		
		Дополнительные параметры:
			ten_file::$debug = true;				// Отладка загрузки
			ten_file::$image_size = 10;				// Максимально возможный вес загружаемых изображений в мегабайтах
			ten_file::$image_type = array('png');	// Массив допустимых типов изображений (по умолчанию gif, png, jpeg). Важно: поддержка других типов изображений не гарантируется
		
		Возможные опции:
			$result = ten_file::upload_image($_FILES['image'], array(
				'path'        => '/assets/',				// Путь для загрузки изображения (слеш на конце не обязателен)
				'name'        => 'image_name',				// Новое имя для загружаемого изображения (без расширения)
				'convert'     => 'jpeg',					// Формат, в который нужно конвертировать изображение (гарантированно поддерживаются: gif, png, jpeg). Важно: jpeg, а не jpg
				'width'       => 100,						// Максимальная ширина
				'height'      => 200,						// Максимальная высота
				'rotate'      => 30,						// Угол поворота в градусах
				'background-rotate' => '167, 255, 147',		// Цвет фона после поворота изображения в формате RGB
				'quality'     => 80,						// Качество изображения (только для jpeg). От 0 до 100
				'mini'	      => true,						// Необходимость добавление миниатюры
				'mini-path'   => '/assets/mini/',			// Путь для загрузки миниатюры
				'mini-name'   => 'mini_{name}',				// Имя для миниатюры, где {name} - это имя оригинального изображения или значение опции 'name'
				'mini-width'  => 50,						// Максимальная ширина миниатюры (по умолчанию 50% от оригинала)
				'mini-height' => 50							// Максимальная высота миниатюры (по умолчанию 50% от оригинала)
			));
			
			В опциях 'name' и 'mini-name' можно использовать служебную переменную {i}, которая в именах загружаемых изображений заменится на их порядковый номер.
			Особенно актуально при загрузхе массива изображений.
			Например:
				'name' 		=> 'prefix_{i}_postfix'
				'mini-name' => 'prefix_{i}_{name}_postfix'
			
			В опциях 'width', 'height', 'mini-width' и 'mini-height' можно использовать как абсолютные значения, так и доли.
			Изменение размеров изображения всегда совершается пропорционально.
			Например:
				'width' => 100			// Максимальная ширина изображения ограничивается 100px
				'width' => 0.7			// Изображение будет уменьшено до 70%
				'width' => 1.2			// Изображение будет увеличено до 120%
				'mini-width' => 50		// Максимальная ширина миниатюры ограничивается 50px
				'mini-width' => 0.3		// Миниатюра будет уменьшена до 30% от исходной ширины оригинала
				'mini-width' => 1.5		// Миниатюра будет увеличина до 150% от исходной ширины оригинала
	
	Объединение файлов:
		$result = ten_file::merge_files(array(
			
			'files'       => 'ext: css, js, ... , etc',		// Обязательный. Мод расширений объединяемых файлов
			// или
			'files'       => 'reg: /\.ctrl\.js$/',			// Обязательный. Мод регулярного выражения

			'input_path'  => '/view/',						// Обязательный. Корневая директория, содержащая объединяемые файлы
			'output_file' => '/assets/{ext}/file.{ext}',	// Обязательный. Выходящий файл
															   Это может быть маска формируемых на выходе файлов при передаче нескольких расширений.
															   Где {ext} - расширение (extension) файла.
															   Переменная существует только при использовании мода расширений ('files' => 'ext: ')
			
			'before'      => "\n start: {filename} { \n",	// Строка, помещаемая перед содержанием очередного файла
			'after'       => "\n } {filename} :end \n",		// Строка, помещаемая после содержания очередного файла
															   Где {filename} - путь и имя текущего файла
			
			'start_str'   => "start { \n",					// Строка, помещаемая в начало файла, по умолчанию отсутствует
			'end_str'     => "\n } end",					// Строка, помещаемая в конец файла, по умолчанию отсутствует
			
			'compress'    => true | false,					// Флаг сжатия конечного файла (работает для CSS и JS), по умолчанию включено
			'recursion'   => true | false					// Флаг рекурсивного перебора дочерних директорий корневой директории, по умолчанию включено
		));
		
		Если в качестве выходящего файла явно указан CSS или JS, то собираемые файлы
		будут скомпрессованы вне зависимости от их истинного расширения,
		например: 'assets/css/style.{ext}.css'
		
		В случае успешной загрузки, в $result возвращается массив с подмассивом путей входящих файлов и 
		подмассивом (или строкой, если было указано одно расширение) выходящих файлов, например:
			Array (
				[input] => Array (
					[0] => view/blocks/dir_1/style.css
					[1] => view/blocks/dir_2/style.css
					[2] => view/blocks/dir_1/script.js
					[3] => view/blocks/dir_2/script.js
					...
				) [output] => Array (
					[0] => assets/css/main.css
					[1] => assets/js/main.js
				)
			)

	Подключение файлов в HTML:
		Простое подключение одного файла:
			echo ten_file::include_files('/assets/css/style.css');

		Подключение нескольких файлов:
			echo ten_file::include_files(array(
				'/assets/css/style.css',
				'/assets/css/manager.css',
				'/assets/js/main.js'
			));

		Подключение файлов с заданием дополнительных опций:
			echo ten_file::include_files(array(				// Обязательный. Массив файлов
				array(										// Массив атрибутов тега подключения файла
					'href'    => 'print.css',
					'media'   => 'print',
					'data-my' => 'param'
				),
				'style.css',								// Просто строка с именем файла
				'manager.css',
				'main.js',
				'info.xml',
				'icon.ico'
			), array(
				'path' => array(							// Массив путей к файлам с конкретными расширениями
					'css' => '/assets/css/',
					'js'  => '/assets/js/',
				 	'xml' => '/assets/xml/',
				 	'ico' => '/assets/'
				),
				'output_file' => '/view/include.tpl',		// Файл для сохранения строк подключений файлов
				'hash' => true | false						// Флаг добавления хеш-метки к файлу (по умолчанию включено)
			));

			Если имя передаётся в виде строки и это не JS-файл, то он автоматически подключается с помощью тега link.
			Если нужно подключить файл с помощью тега script, то требуется подключение в виде массива с передачей атрибута src:
			array(
				'src' => 'filename.java'
			)
*/

class ten_file {
	
	/**
	 * Конструктор
	 *
	 */
	private function __construct() {
		
		ten_file::$image_size *= 1024 * 1024;						// Перевод мегабайтов в байты
	}
	
	/**
	 * Функция сохранения массива в файл
	 *
	 * @param  string $filename Имя файла
	 * @param  array $array Массив для записи в файл
	 * @return array
	 */
	public static function save_arr($filename, $array) {
		
		$filename = ROOT . $filename;
		file_put_contents($filename, serialize($array));
		chmod($filename, 0644);
	}
	
	/**
	 * Функция чтения массива из файла
	 *
	 * @param  string $filename Имя файла
	 * @return array
	 */
	public static function read_arr($filename) {
		
		return unserialize(file_get_contents(ROOT . $filename));
	}
	
	private static $image_path = '/assets/images/';					// Директория по умолчанию для загрузки изображений
	
	private static $path_array = array();							// Массив для вывода путей загруженных изображений
	
	public  static $image_size = 4;									// Вес загружаемого изображения в мегабайтах
	public  static $image_type = array('gif', 'png', 'jpeg');		// Допустимые типы файлов для изображений
	
	public  static $debug = false;									// Флаг отладки скрипта для вывода ошибок
	
	/**
	 * Функция загрузки изображения
	 *
	 * @param array $files   Массив $_FILES с необходимым файлом
	 * @param array $options Массив дополнительных параметров (path, width, height)
	 * @return mixed
	 */
	public static function upload_image($files, $options = array()) {
		
		new ten_file;												// Создание объекта для запуска конструктора, чтобы перевести допустимые размеры загружаемого изображения в байты
		
		$files_array = array();										// Массив загружаемых изображений
		
		foreach($files as $key => $val_arr)							// Цикл по массиву $_FILES
			if(gettype($val_arr) == 'array') {						// Если текущий элемент массива $_FILES является массивом
				foreach($val_arr as $val_num => $val_val)			// то по нему нужно пройтись
					if($files['size'][$val_num] > 0)				// Если размер текущего элемента больше нуля, то есть файл существует
						$files_array[$val_num][$key] = $val_val;	// его нужно добавить в новый массив изображений для загрузки
			}
			else {													// Иначе текущий элемент не массив, то есть загружается одно изображение
				
				$files_array[0] = $files;							// Массив изображений для загрузки будет состоять из одного элемента
				break;												// Выход из цикла по массиву $_FILES
			}
		
		if($options['mini']) {												// Если требуется загрузка миниатюр
			
			$mini_options = $options;										// Переприсваивание массива опций
			
			unset($mini_options['mini']);									// Удаление элемента, сообщающего о необходимости добавления миниатюр
			$mini_options['mini-upload'] = true;							// Добавление элемента, символизирующего о загрузке миниатюр
			
			if(isset($options['mini-path']))								// Если задан путь для загрузки миниатюры
				$mini_options['path']   = $options['mini-path'];			// его нужно переприсвоить
			
			if(isset($options['mini-name'])) {								// Если задано имя для миниатюры
				
				if(isset($options['name']))									// Если задано имя для оригинала
					$name = str_replace('{name}', $options['name'], $options['mini-name']);	// Замена служебной переменной на имя оригинала
				else														// Иначе для оригинала имя не задано
					$name = $options['mini-name'];							// тогда имя для миниатюры нужно просто переприсвоить
				
				$mini_options['name']   = $name;							// Присваивание полученного имени для имени миниатюры
			}
			else															// Иначе имя для миниатюры не задано
				$mini_options['name']   = 'mini_' . $options['name'];		// Тогда формируется стандартное имя для миниатюры
			
			if(isset($options['mini-width']))								// Если задана ширина для миниатюры
				$mini_options['width']  = $options['mini-width'];			// то её надо переприсвоить
			else															// Иначе ширина для миниатюры не задана
				$mini_options['width']  = 0.5;								// тогда ширина для миниатюры по умолчанию должна быть равна половине от оригинала
			
			if(isset($options['mini-height']))								// Если задана высота для миниатюры
				$mini_options['height'] = $options['mini-height'];			// то её надо переприсвоить
			else															// Иначе высота для миниатюры не задана
				$mini_options['height'] = 0.5;								// тогда высота для миниатюры по умолчанию должна быть равна половине от оригинала
			
			ten_file::$path_array['originals']  = array();					// Создание массива в массиве для путей	к оригинальным изображениям
			ten_file::$path_array['miniatures'] = array();					// Создание массива в массиве для путей	к миниатюрам изображений
		}
		
		foreach($files_array as $num => $file) {
			
			if(empty($options['path']))												// Если путь не указан
				$options['path'] = ten_file::$image_path;							// то нужно использовать умолчания
			else if(substr($options['path'], -1) != '/')							// Иначе если в конце забыт слеш
				$options['path'] .= '/';											// его нужно добавить
			
			if(empty($options['name'])) {											// Если новое имя для файла не указано
				$name = substr($file['name'], 0, strripos($file['name'], '.'));		// то используется первоначальное, но без расширения
				if($options['mini'])												// Если требуется загрузка миниатюр
					$mini_options['original-name'][$num] = $name;					// то нужно сохранить имя оригинала
			}
			else																	// Иначе имя указано
				$name = $options['name'];											// и его надо просто переприсвоить
			
			$name = str_replace('%', '', $name);									// Удаление символов процента из имени файла
			$name = str_replace('{i}', $num, $name);								// Замена служебной переменной итерации на порядковый номер изображения
			$name = str_replace('{name}', $options['original-name'][$num], $name); 	// Замена служебной переменной имени оригинала на соответствующее имя
			
			preg_match('/^image\/(.*)$/', $file['type'], $type);			// Регулярное выражение на определение типа контента
			
			if(!$type[1] || !in_array($type[1], ten_file::$image_type)) {	// Если текущий файл не изображение или если изображение, но имеет не поддерживаемый тип
				
				if(ten_file::$debug)										// Если включена отладка
					error::print_error('<b>Upload error:</b> Bad image type!');
				
				return -1;													// Ошибка -1: Неверный тип файла
			}
			
			if($file['size'] > ten_file::$image_size) {						// Если размер загружаемого файла не соответствует ограничению
				
				if(ten_file::$debug)										// Если включена отладка
					error::print_error('<b>Upload error:</b> Big image size, maximum = ' . (ten_file::$image_size / 1024 / 1024) . ' megabites (' . ten_file::$image_size . ' bytes). And your file size = ' . $file['size'] . ' bytes');
				
				return -2;													// Ошибка -2: Слишком большой размер загружаемого файла
			}
			
			$image_info = getimagesize($file['tmp_name']);					// Массив информации о загружаемом изображении
			
			if(empty($image_info)) {
				
				if(ten_file::$debug)										// Если включена отладка
					error::print_error('<b>Upload error:</b> can\'t read information about file');
				
				return -3;													// Ошибка -3: Отсутствует информация по изображению (скорее всего это не изображение)
			}
				
			$types = array(													// Массив типов изображений в соответствии с возвращаемыми флагами функции getimagesize()
				1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'swf', 
				5 => 'psd', 6 => 'bmp', 7 => 'tiff', 8 => 'tiff', 
				9 => 'jpc', 10 => 'jp2', 11 => 'jpx'
			);
			
			if(!file_exists(ROOT . $options['path']))						// Если указанного пути не существует
				if(!mkdir(ROOT . $options['path'], 0, true)) {				// Если не удалось создать каталоги, указанные в пути
					
					if(ten_file::$debug)									// Если включена отладка
						error::print_error('<b>Upload error:</b> can\'t find and make directory');
					
					return -4;												// Ошибка -4: Не найден заданный путь и невозможно его создать
				}
			
			$func_imagecreatefrom = 'imagecreatefrom' . $type[1];			// Создание имени функции в соответствии с типом изображения
			$base_image = $func_imagecreatefrom($file['tmp_name']);			// Создание нового изображения из добавленного файла
			
			if(!empty($options['rotate'])) {								// Если указана опция поворота изображения
				
				if(empty($options['background-rotate']))					// Если не указан цвет для фона после поворота изображения
					$bckg_color = -1;										// то устанавливается прозрачный цвет
				else {														// Иначе цвет указан
					
					$bckg_colors = explode(',', $options['background-rotate']);
					
					$background_color['red']   = intval($bckg_colors[0]);
					$background_color['green'] = intval($bckg_colors[1]);
					$background_color['blue']  = intval($bckg_colors[2]);
					
					$bckg_color = imagecolorallocate(						// Задание цвета для фона после поворота изображения
						$base_image,
						$background_color['red'],
						$background_color['green'],
						$background_color['blue']
					);
				}
				
				$base_image = imagerotate($base_image, $options['rotate'], $bckg_color);
			}
			
			$base_width  = imagesx($base_image);							// Определение ширины базового изображения
			$base_height = imagesy($base_image);							// Определение высоты базового изображения
			
			if(!empty($options['width']) || !empty($options['height'])) {		// Если задан какой-либо параметр размеров
				
				if(empty($options['width']))									// Если опция ширины не задана
					$width = $base_width;										// то ширина остаётся базовой
				else if(gettype($options['width']) == 'double')					// Иначе ширина задана и является дробным числом
					$width = round($base_width * $options['width']);			// тогда ширина высчитывается в соответствии указанной доле
				else															// Иначе ширина задана конкретно
					$width = $options['width'];									// тогда нужно её просто переприсвоить
				
				if(empty($options['height']))									// Если опция высоты не задана
					$height = $base_height;										// то высота остаётся базовой
				else if(gettype($options['height']) == 'double')				// Иначе высота задана и является дробным числом
					$height = round($base_height * $options['height']);			// тогда высота высчитывается в соответствии указанной доле
				else															// Иначе высота задана конкретно
					$height = $options['height'];								// тогда нужно её просто переприсвоить
				
				if($base_width != $width) {										// Если ширина изображения не соответствует новой ширине
						
					$ratio  = $base_width / $width;								// Высчитывается соотношение сторон
					$width  = round($base_width  / $ratio);						// Задаётся новая ширина
					$height = round($base_height / $ratio);						// Задаётся новая высота
				}
				else if($base_height != $height) {								// Если высота изображения не соответствует новой высоте
					
					$ratio  = $base_height / $height;							// Высчитывается соотношение сторон
					$width  = round($base_width  / $ratio);						// Задаётся новая ширина
					$height = round($base_height / $ratio);						// Задаётся новая высота
				}
				
				$new_image = imagecreatetruecolor(								// Создание нового пустого изображения
					$width, $height
				);
				
				imagealphablending($new_image, false);							// Устанавливается режим смешивания для изображения
				imagesavealpha($new_image, true);								// Устанавливается сохранение альфа-канала
				
				imagecopyresampled(												// Копирование старого изображения в новое с изменением параметров
					$new_image, $base_image,
					0, 0, 0, 0,
					$width, $height, $base_width, $base_height
				);
			}
			else {
				
				$new_image = imagecreatetruecolor(								// Создание нового пустого изображения
					$base_width, $base_height
				);
				
				imagealphablending($new_image, false);							// Устанавливается режим смешивания для изображения
				imagesavealpha($new_image, true);								// Устанавливается сохранение альфа-канала
				
				imagecopy(														// Иначе масштабировать изображение не требуется и нужно просто его скопировать
					$new_image, $base_image,
					0, 0, 0, 0,
					$base_width, $base_height
				);
			}
			
			if(empty($options['convert'])) {									// Если не указан тип конечного изображения
				
				$extension = $types[$image_info[2]];
				$image_type = $type[1];											// то используется первоначальный тип
			}
			else {																// Иначе конечный тип указан
				
				$extension  = $options['convert'];								// и нужно использовать его
				$image_type = $options['convert'];
			}
			
			$path = ROOT . $options['path'] . $name . '.' . $extension;			// Полный путь к загружаемому изображению
			
			$func_image = 'image' . $image_type;								// Создание имени функции в соответствии с типом изображения
			
			if($image_type == 'jpeg') {											// Если тип изображения jpeg
				
				if(empty($options['quality']))									// Если опция качества не указана
					$options['quality'] = 100;									// то качество должно быть наилучшим
				
				$result = $func_image($new_image, $path, $options['quality']);	// то нужно применить параметр качества
			}
			else																// Иначе тип изображения любой другой
				$result = $func_image($new_image, $path);						// и параметр качества применить невозможно
			
			imagedestroy($base_image);											// Удаление из памяти базового изображения
			imagedestroy($new_image);											// Удаление из памяти добавленного изображения
			
			if($result) {														// Если файл загружен
				
				if($options['mini'])											// Если требуется загрузка миниатюр
					array_push(ten_file::$path_array['originals'], $path);		// то сейчас загружаются оригиналы и нужно пополнить в массив путей
				else if($options['mini-upload'])								// Иначе если сейчас загружаются миниатюры
					array_push(ten_file::$path_array['miniatures'], $path);		// нужно добавить путь в подмассив миниатюр
				else															// Иначе загрузка миниатюр не требуется
					array_push(ten_file::$path_array, $path);					// и нужно просто добавить путь в массив путей
				
				if(count($files_array) == 1) {									// Если требуется загрузить всего один файл
					
					if($options['mini'])										// Если требуется загрузка миниатюр
						return ten_file::upload_image($files, $mini_options); 	// Рекурсивный вызов функции для загрузки миниатюр
					
					if(count(ten_file::$path_array) > 1) {						// Если было загружено больше одного изображения
						
						if(ten_file::$debug)									// Если включена отладка
							message::print_message('Upload images array is complete');
						
						return ten_file::$path_array;							// Возвращение массива путей
					}
					else {
						
						if(ten_file::$debug)									// Если включена отладка
							message::print_message('Upload complete to <b>' . $path . '</b>');
						
						return $path;											// Функция возвращает путь к загруженному файлу
					}
				}
			}
			else {																// Иначе файл не загрузился
				
				if(ten_file::$debug)											// Если включена отладка
					error::print_error('<b>Upload error:</b> ' . $path);
				
				return false;													// Неизвестная ошибка
			}
		}
																				// Если программа попадает сюда, то был успешно
																				// загружен массив файлов
		if($options['mini'])													// Если требуется загрузка миниатюр
			return ten_file::upload_image($files, $mini_options);				// Рекурсивный вызов функции для загрузки миниатюр
		
		if(ten_file::$debug)													// Если включена отладка
			message::print_message('Upload images array is complete');
		
		return ten_file::$path_array;											// Функция возвращает массив путей к загруженным файлам
	}
	
	private static $folder_array = array();										// Массив директорий
	private static $input_files  = array();										// Массив путей объединённых файлов
	private static $output_file;												// Строка, в которую собираются файлы
	
	private static $default_merge_options = array(								// Дефолтные параметры объединения файлов
		'before' => '',
		'after'  => '',
		'start_str' => '',
		'end_str'   => '',
		'compress'  => true,
		'recursion' => true
	);
	
	/**
	 * Функция объединения файлов
	 *
	 * @param  array $options Параметры объединения файлов
	 * @return array
	 */
	public static function merge_files($options) {
		
		$options['input_path']  = ten_text::rgum($options['input_path'], '/');			// Добавление слеша в конец пути корневой директории, если его там нет
		$options['input_path']  = ROOT . $options['input_path'];						// Абсолютный путь корневой директории
		$options['output_file'] = ROOT . $options['output_file'];						// Абсолютный путь выходящего файла

		foreach(ten_file::$default_merge_options as $key => $val)						// Установка значений по умолчанию
			if(!isset($options[$key]))													// для незаданных опций
				$options[$key] = $val;

		$files = explode(':', $options['files']);										// Разбиение строки объединяемых файлов в массив
		$files_mod = trim($files[0]);													// Мод поиска файлов (ext или reg)
		
		if($files_mod == 'ext')															// Если задан мод расширений
			$files_val    = explode(',', $files[1]);									// то строку значения надо разбить в массив расширений
		else if($files_mod == 'reg')													// Если задан мод регулярного выражения
			$files_val[0] = $files[1];													// то достаточно просто переприсвоить строку значения
		
		if(
			$files_mod == 'reg' || 														// Если задан мод регулярного выражения
			$files_mod == 'ext' && count($files_val) == 1								// или мод расширений и указано всего одно расширение
		) {
			$output = ten_file::merge_file($files_mod, trim($files_val[0]), $options);	// То можно просто вызвать функцию объединения один раз
		}
		else {																			// Иначе задан мод расширений и указано больше одного расширения
			
			$output = array();															// Массив для путей собранных файлов
			
			foreach($files_val as $extension)											// Цикл по полученным расширениям
				array_push(																// Добавление
					$output,															// в массив путей
					ten_file::merge_file($files_mod, trim($extension), $options)		// результата слияния файлов
				);
		}
		
		$input = ten_file::$input_files;
		ten_file::$input_files = array();												// Обнуление файла путей объединённых файлов
		
		return array(
			'input'  => $input,
			'output' => $output
		);
	}
	
	/**
	 * Функция непосредственного объединения файлов
	 *
	 * @param  string $mod       Мод поиска файлов (ext или reg)
	 * @param  string $val       Значение поиска файлов (расширение или регулярное выражение)
	 * @param  array  $options   Параметры объединения файлов
	 * @return string
	 */
	private static function merge_file($mod, $val, $options) {
		
		$output_extension = end(explode('.', $options['output_file']));			// Расширение выходящего файла

		if($input = opendir($options['input_path'])) {							// Если открылась первоначальная директория
			
			while($object = readdir($input)) {									// Цикл по объектам в текущей директории
				
				if($object != '.' && $object != '..') {							// Если текущий объект является файлом или директорией
					
					$directory = $options['input_path'] . $object . '/';
					
					if(
						is_dir($directory) &&									// Если текущий объект является директорией
						$options['recursion']									// и требуется рекурсивный перебор директорий
					) {

						array_push(ten_file::$folder_array, $directory);		// он добавляется в массив директорий
					}
					else if (													// Иначе текущий объект - это файл
						$mod == 'ext' && 										// Если задан мод расширений
						end(explode('.', $object)) == $val ||					// и расширение текущего файла соответствует заданному для поиска
						
						$mod == 'reg' &&										// Или задан мод регулярного выражения
						preg_match($val, $object)								// и имя текущего файла удовлетворяет условия регулярного выражения
					) {
						
						$file = $options['input_path'] . $object;				// Полный путь к файлу
						
						array_push(ten_file::$input_files, $file);				// Добавление пути текущего файла в массив путей объединённых файлов
						
						$before = str_replace('{filename}', $file, $options['before']);
						$after  = str_replace('{filename}', $file, $options['after']);

						ten_file::$output_file .=								// Добавление
							$before                  .							// предваряющей строки
							file_get_contents($file) .							// к содержанию текущего файла
							$after;												// и последующей строки
					}
				}
			}
			
			if(count(ten_file::$folder_array)) {								// Если имеются непросмотренные директории
				
				$options['input_path'] = ten_file::$folder_array[0];			// Задание новой директории для дальнейшего рекурсивного вызова функции
				array_shift(ten_file::$folder_array);							// Удаление присвоенной директории из массива непросмотренных директорий
				return ten_file::merge_file($mod, $val, $options);				// Рекурсивный вызов функции
			}
			
			closedir($input);													// Закрытие текущего объекта
		}
		else
			error::print_error('Can\'t open directory: ' . $options['input_path']);
		
		$extension = ($mod == 'ext') ? $val : '';								// Переменная расширения будет существовать только когда задан мод расширений

		$output_file = str_replace('{ext}', $extension, $options['output_file']);

		ten_file::$output_file = 												// Добавление
			$options['start_str']  . 											// первой строки
			ten_file::$output_file . 											// к выходящему файлу
			$options['end_str'];												// и последней строки
		
		if($extension == 'css' || $output_extension == 'css') {					// Если текущее расширение или расширение выходящего файла является CSS
			
			if(is_null($options['compress']) || $options['compress'])			// Если сжатие конечного файла не отключено
				ten_file::$output_file = trim(str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',ten_file::$output_file))))));
		}
		else if($extension == 'js' || $output_extension == 'js') {				// Если текущее расширение или расширение выходящего файла является JS
			
			if(is_null($options['compress']) || $options['compress'])			// Если сжатие конечного файла не отключено
				ten_file::$output_file = trim(ten_file_jsmin::minify(ten_file::$output_file));
		}
		
		file_put_contents($output_file, ten_file::$output_file);				// Запись итоговой строки в выходящий файл
		chmod($output_file, 0644);												// Присвоение необходимых прав на файл

		ten_file::$output_file = '';											// Обнуление строки собранного файла
		
		return $output_file;													// Возвращается путь к составленному файлу
	}
	
	/**
	 * Функция формирования строки подключения CSS- и JS-файлов к HTML
	 *
	 * @param  string | array $files   Имя файла для подкючения или массив имён
	 * @param  array          $options Параметры формирования строки подключения
	 * @return string
	 */
	public static function include_files($files, $options = null) {
		
		if(gettype($files) == 'string')											// Если нужно подключить один файл
			$included = ten_file::include_file($files, $options);				// Возвращается строка подключения файла
		
		else if(gettype($files) == 'array') {									// Если нужно подключить массив файлов
			
			$included = '';														// Объявление результирующей строки
			
			foreach($files as $file)											// Цикл по массиву файлов
				$included .= ten_file::include_file($file, $options) . "\n";	// Добавление строки подключения файла в результирующую строку
		}

		if(!empty($options['output_file'])) {									// Если указан файл для сохранения результата
			
			$output_file = ROOT . $options['output_file'];
			file_put_contents($output_file, $included);							// сохранение конечной строки в файл
			chmod($output_file, 0644);											// Присвоение необходимых прав на файл
		}

		return $included;														// Возвращение строки подключения всех файлов
	}

	/**
	 * Функция непосредственного подключения файла
	 *
	 * @param  string | array $files   Имя файла для подкючения
	 * @param  array          $options Параметры формирования строки подключения
	 * @return string
	 */
	private static function include_file($file, $options) {
		
		$def_attrs = array(															// Массив дефолтных значений атрибутов

			'link' => array(														// для тега link
				'href'    => '',
				'rel'     => 'stylesheet',
				'type'    => 'text/css',
				'media'   => '',
				'charset' => '',
				'sizes'   => ''
			),

			'script' => array(														// для тега script
				'src'      => '',
				'type'     => '',
				'language' => '',
				'defer'    => ''
			)
		);

		switch(gettype($file)) {
			
			case 'array':															// Если текущий файл представлен в виде массива

				if(!empty($file['src'])) {											// Если указан атрибут src
					
					$url = 'src';
					$tag = 'script';
				}
				else if(!empty($file['href'])) {									// иначе если указан атрибут href

					$url = 'href';
					$tag = 'link';
				}
				else																// иначе ни href ни src не указаны и надо вывести ошибку
					error::print_error('Can\'t read attribute "href" or "src" of include file');

				$type = end(explode('.', $file[$url]));								// Расширение файла
				$attrs = array_merge($def_attrs[$tag], $file);						// Слияние массива дефолтных атрибутов с переданным массивом атрибутов

				break;
			
			case 'string':															// Если текущий файл представлен строкой

				$type = end(explode('.', $file));									// Расширение файла

				switch($type) {

					case 'js':														// Если js-файл
						$url = 'src';
						$tag = 'script';
						break;

					default:														// Если иной файл (css, ico, xml, etc)
						$url = 'href';
						$tag = 'link';
				}

				$attrs = $def_attrs[$tag];											// Указание массива с дефолтными значениями
				$attrs[$url] = $file;												// Задание значения для атрибута пути/имени файла
		}

		if(isset($options['path']))
			$attrs[$url] = $options['path'][$type] . $attrs[$url];					// Добавление пути к файлу
		
		if(!isset($options['hash']) || $options['hash'])							// Если нужно добавить хеш файла
			$attrs[$url] .= '?' . md5_file(ROOT . $attrs[$url]);					// добавление хеша в строку пути файла

		$attrs_str = '';

		foreach($attrs as $attr => $val)											// Цикл по атрибутам
			if(!empty($val))														// Если значение атрибута не пустое
				$attrs_str .= ' ' . $attr . '="' . $val . '"';						// его надо добавить в тег

		switch($tag) {
			
			case 'script':															// Если тег script
				return '<script' . $attrs_str . '></script>';

			case 'link':															// Если тег link
				return '<link' . $attrs_str . '>';
		}
	}
}