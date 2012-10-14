<div class="info">
	<h1 id="/frontender"><a href="https://gist.github.com/3878795">Задание для верстальщика</a></h1>

	<h2 id="/frontender-css">Про CSS</h2>
	{{ $rating-control }}
	{{ $progress-bar   }}
	{{ $button         }}

	<h2 id="/frontender-js">Про JavaScript</h2>
	<p>Я немного увеличил исходный массив для появления двойных значений.</p>
	<div class="info__copy-code"><pre><code class="javascript">var hashClasses = {

        fullClasses: [
            'b-statcounter',
            'b-statcounter',
            'b-statcounter',
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

            var fullCls  = hashClasses.fullClasses,             // Массив полных имён классов
                shortCls = hashClasses.shortClasses,            // Массив кратких имён классов
                hash = {},                                      // Объект для конечного вывода
                sort = [],                                      // Массив для сортировки классов по количеству
                value,                                          // Переменная для хранения количества класса предыдущей итерации
                shortI = -1;                                    // Итератор массива кратких имён классов
            
            for(var c = 0; c < fullCls.length; c++) {           // Цикл по массиву полных имён классов

                if(fullCls[c] in hash)                          // Если класс уже был добавлен
                    hash[fullCls[c]]++;                         // Нужно увеличить его количество
                else
                    hash[fullCls[c]] = 1;                       // Иначе его количество равно единице
            }

            for(var h in hash)                                  // Цикл по свойстам сформированного объекта
                sort.push([h, hash[h]]);                        // Добавление класса и его количества в массив сортировки

            sort.sort(function(a, b) { return b[1] - a[1]; });  // Сортировка массива по количеству классов (по убыванию)

            for(var e = 0; e < sort.length; e++) {              // Цикл формирования итоговой таблицы соответствий

                if(value != sort[e][1]) {                       // Если количество повторов предыдущего класса отличается от количества текущего

                    shortCls[shortI] +=                         // Добавление к текущему короткому классу
                        String(shortCls[shortI++])              // Его первого символа и инкрементирование итератора массива кратких классов
                            .substr(0, 1);

                    if(shortI >= shortCls.length)               // Если итератор вышел за пределы массива
                        shortI = 0;                             // Нужно начать итерации заново
                }

                hash[sort[e][0]] = shortCls[shortI];            // Назначение соответствующего краткого класса

                value = sort[e][1];                             // Сохранение количества повторов текущего класса
            }

            return hash;                                        // Возвращение итоговой таблицы соответствий
        }
    };

    console.log(hashClasses.getHash());</code></pre></div>
</div>