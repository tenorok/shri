/** settings:

	saveless: {
		
		path: '/assets/css/',		// Директория, в которую будут сохраняться файлы
		compress: true | false,		// Флаг компрессии выходящих css-файлов (по умолчанию true)
	}
*/

/** routes:
	
	url:   Адрес, при переходе по которому осуществляется вызов
		Примеры адресов:
			'/'
			'/url/my/'
			'/url/#/hash'
			'/url/{id}/'
		Или сразу несколько адресов:
			[
				'/',
				'/url/my/'
			]
	ctrl:  Контроллер
	func:  Метод контроллера
	rules: Правила для переменных
		Примеры правил:
			{
				id: /\d+/,
				name: /^myname$/
			}
	call:  Способ проведения маршрута
		Возможные значения:
			ever - каждый раз при любом изменении адресной строки (по умолчанию)
			load - единожды при загрузке страницы

*/
{
	var settings = {

		saveless: {

			path: '/assets/css/'
		}
	};
	
	var routes = [
		{
			url: [
				'/',
				'/#/{anchor}'
			],
			ctrl: 'index',
			func: 'init',
			call: 'load'
		},
		{
			url:  '/print',
			ctrl: 'index',
			func: 'initPrint',
			call: 'load'
		}
	];
};