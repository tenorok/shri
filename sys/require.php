<?php

session_start();

define('ROOT', $_SERVER['DOCUMENT_ROOT']);						// Константа для удобства применения

define('BLOCKS',     ROOT . '/view/blocks/');					// Константа директории блоков

// Определение констант для автоподключения классов
define('SYS',        ROOT . '/sys/classes/');					// Определение директории с классами системы
define('CONTROLLER', ROOT . '/app/controller/');				// Определение директории с классами контроллеров
define('MODEL',      ROOT . '/app/model/');						// Определение директории с классами модели

define('DEV', true);											// Вкл/выкл режима разработчика

require 'core.php';												// Подключение ядра
spl_autoload_register(array('core', 'auto_load'));				// Включение автоподгрузки классов

if(!DEV)														// Если выключен режим разработчика
	error_reporting(0);											// Отключение отображения ошибок интерпретатора
else
	error_reporting(E_ALL);										// Включение отображения всех ошибок интерпретатора

register_shutdown_function(array('error', 'get_error'));		// Указание метода, который будет вызван по окончании выполнения всего скрипта

// orm::connect('localhost', 'root', '');						// Подключение к mysql
// orm::db('dbname');											// Выбор базы данных