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
	ctrl:  Контроллер
	func:  Метод контроллера
	rules: Правила для переменных
		Примеры правил:
			{
				id: /\d+/,
				name: /^myname$/
			}

*/
{
	var settings = {

		saveless: {

			path: '/assets/css/'
		}
	};
	
	var routes = [
		// {
		// 	url: '/',
		// 	ctrl: 'controller',
		// 	func: 'method'
		// }
	];
};