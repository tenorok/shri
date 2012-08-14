<?php

// Сохранение LESSCSS

require $_SERVER['DOCUMENT_ROOT'] . '/sys/require.php';

switch($_POST['event']) {
	
	case 'save_lesscss':												// Событие сохранения
		
		$css = json_decode($_POST['css']);								// Массив имя_файла->контент

		foreach($css as $file => $val) {
			
			if($_POST['compress'] == 'true')							// Если нужна компрессия
				$val = trim(str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$val))))));
			
			$filename = ROOT . $_POST['path'] . $file;
			file_put_contents($filename, $val);							// Сохранение файла
			chmod($filename, 0644);										// Присвоение необходимых прав на файл
		}
		
		break;
}