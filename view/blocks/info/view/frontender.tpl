<div class="info">
	<h1 id="/frontender"><a href="https://gist.github.com/3878795">Задание для верстальщика</a></h1>

	<h2 id="/frontender-css">Про CSS</h2>
	{{ $rating-control }}
	{{ $progress-bar   }}
	{{ $button         }}

	<h2 id="/frontender-js">Про JavaScript</h2>
    
    <h3>Сокращение классов</h3>
	<div class="info__copy-code"><pre><code class="javascript">var hashClasses = {

        fullClasses: [
            'b-statcounter',
            'b-statcounter__metrika',
            'b-statcounter__metrika_type_js',
            'i-bem',
            'b-search__table',
            'b-form-input',
            'b-form-input_theme_grey',
            'b-form-input_size_l',
            'i-bem',
            'b-form-input__input',
            'b-search__button',
            'b-form-button__content',
            'b-form-button__text',
            'b-form-button__input',
            'i-bem',
            'b-main-menu',
            'b-main-menu__tab',
            'b-main-menu__tab',
            'b-main-menu__tab',
            'b-main-menu__tab',
            'b-main-menu__tab_type_selected',
            'b-main-menu__tab',
            'b-main-menu__tab_type_selected',
            'l-page__right',
            'b-static-text',
            'b-static-text',
            'b-foot__layout-column',
            'b-foot__layout-column_type_left',
            'b-link',
            'b-foot__link',
            'b-foot__layout-column',
            'b-foot__layout-column',
            'b-foot__layout-column_type_center',
            'b-link',
            'b-foot__link',
            'b-foot__layout-column',
            'b-foot__layout-column_type_penultima',
            'b-link',
            'b-foot__link',
            'b-foot__layout-column',
            'b-foot__layout-column_type_right',
            'b-copyright__link',
            'b-foot__layout-gap-i'
        ],

        shortClasses: ['a', 'b', 'c', 'd'],

        getHash: function() {

            var fullCls  = hashClasses.fullClasses,                 // Массив полных имён классов
                hash = {},                                          // Объект для конечного вывода
                sort = [];                                          // Массив для сортировки классов по количеству
            
            for(var c = 0; c < fullCls.length; c++) {               // Цикл по массиву полных имён классов

                if(fullCls[c] in hash)                              // Если класс уже был добавлен
                    hash[fullCls[c]]++;                             // Нужно увеличить его количество
                else
                    hash[fullCls[c]] = 1;                           // Иначе его количество равно единице
            }

            for(var h in hash)                                      // Цикл по свойстам сформированного объекта
                sort.push([h, hash[h]]);                            // Добавление класса и его количества в массив сортировки

            sort.sort(function(a, b) { return b[1] - a[1]; });      // Сортировка массива по количеству классов (по убыванию)

            var listShCls = hashClasses.getShortClasses(            // Получение сформированного массива всех необходимых кратких имён классов
                sort.length,
                hashClasses.shortClasses
            );

            hash = {};                                              // Обнуление возвращаемого объекта

            for(var e = 0; e < sort.length; e++)                    // Цикл формирования итоговой таблицы соответствий
                hash[sort[e][0]] = listShCls[e];

            return hash;                                            // Возвращение итоговой таблицы соответствий
        },

        allShrotClasses: [],                                        // Массив для хранения всех сформированных кратких имён классов

        getShortClasses: function(nodeCount, prevClasses) {         // Функция формирования массива всех необходимых кратких имён классов

            var shortCls = hashClasses.shortClasses,                // Массив кратких имён классов
                allShCls = hashClasses.allShrotClasses,             // Массив хранения всех сформированных кратких имён классов
                newClasses = [];                                    // Массив для формирования новых имён классов

            for(var p = 0; p < prevClasses.length; p++) {           // Цикл по массиву классов, сформированному в прошлый раз

                for(var s = 0; s < shortCls.length; s++) {          // Цикл по заданному массиву кратких классов

                    if(allShCls.length >= nodeCount - shortCls.length) // Если сгенерировано достаточное количество кратких классов
                        return shortCls.concat(allShCls);           // Нужно вернуть итоговый массив кратких классов

                    var className = prevClasses[p] + shortCls[s];   // Формирование нового имени класса

                    allShCls.push(className);                       // Добавление нового имени класса в массив всех классов
                    newClasses.push(className);                     // Добавление нового имени класса в массив новых классов
                }
            }
            
            return hashClasses.getShortClasses(                     // Рекурсивный вызов функции формирования массива кратких классов
                nodeCount,
                newClasses
            );
        }
    };

    console.log(hashClasses.getHash());</code></pre></div>

    <h3><a href="http://company.yandex.ru/job/vacancies/trainee_javascript.xml">Вывод односвязного списка в обратном порядке (вопрос 15)</a></h3>
    <div class="info__copy-code"><pre><code class="javascript">function reversePrint(linkedList) {
        
    var value = linkedList.value,
        next  = linkedList.next,
        out   = [];
    
    while(true) {
        
        out.push(value);
        
        if(next == null)
            break;
        
        value = next.value;
        next  = next.next;
    }
    
    console.log(out.reverse().join(', '));
}
     
var someList = {
    value: 1,
    next: {
        value: 2,
        next: {
            value: 3,
            next: {
                value: 4,
                next: null
            }
        }
    }
};
 
reversePrint(someList);</code></pre></div>
</div>