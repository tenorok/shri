<?php

ten_file::merge_files(array(							// Сборка всех js-файлов
	'files'       => 'ext: js',
	'input_path'  => '/view/',
	'output_file' => '/assets/js/main.js'
));

ten_file::merge_files(array(							// Сборка основных стилей и необходимых библиотек
	'files'       => 'reg: /\.style|\.import/',
	'input_path'  => '/view/',
	'output_file' => '/assets/css/main.less'
));

ten_file::merge_files(array(							// Сборка стилей для печати и необходимых библиотек
	'files'       => 'reg: /\.print|\.import/',
	'input_path'  => '/view/',
	'output_file' => '/assets/css/print.less'
));

ten_file::merge_files(array(							// Сборка ie-стилей
	'files'       => 'ext: ie567, ie',
	'input_path'  => '/view/',
	'output_file' => '/assets/css/style.{ext}.css'
));