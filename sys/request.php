<?php

/**
 * Функция обработки маршрутов, отправленных методом GET
 *
 * @param string $path     Путь, указанный в роуте
 * @param string $callback Класс->Метод для вызова
 * @param array  $asserts  Массив регулярных выражений для проверки {переменных}
 */
function get($path, $callback, $asserts = array()) {
	
	core::request('GET', $path, $callback, $asserts);
}

/**
 * Функция обработки маршрутов, отправленных методом POST
 *
 * @param string $path     Путь, указанный в роуте
 * @param string $callback Класс->Метод для вызова
 * @param array  $asserts  Массив регулярных выражений для проверки {переменных}
 */
function post($path, $callback, $asserts = array()) {
	
	core::request('POST', $path, $callback, $asserts);
}