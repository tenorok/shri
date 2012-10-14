<div class="info">
	<h1 id="/tech"><a href="http://clubs.ya.ru/shri/replies.xml?item_no=42">Технические вопросы</a></h1>
	
	<h2 id="/tech-tools">Отладка</h2>
	<p>Для разработки я предпочитаю использовать Mozilla Firefox. Для этого браузера есть великолепное дополнение Firebug, которое сильно облегчает работу с фронтендом. Так же для отладки в этом браузере я использовал дополнение просмотра HTTP заголовков. Кроме того, есть хорошее дополнение Web Developer Toolbar.</p>
	<p>В Chrome и Safari есть встроенный инспектор. А в Opera есть свой Dragonfly.</p>
	<p>В IE 9+ появились средства разработчика, у которых есть удобная особенность включения режима седьмой и восьмой версии. Для тестирования ранних версий IE можно использовать IETester, однако у него полно своих собственных багов и нет ничего надёжнее проверки в настоящем браузере определённой версии.</p>

	<h2 id="/tech-click">Клик по ссылке</h2>
	<p>При клике на ссылку yandex.ru, в браузере возникает событие OnClick. Браузер выполняет HTTP-запрос к http://yandex.ru и ждёт ответ. Сервер  возвращает браузеру сформированную страницу.</p>

	<h2 id="/tech-auto">Автосалон</h2>
	<div class="info__copy-code"><pre><code class="javascript">/**
 * Создает экземпляр Машины
 * @this {Car}
 * @param {string} manufacturer Производитель
 * @param {string} model Модель
 * @param {number} year Год производство
 */
function Car(manufacturer, model, year) {
    
    if(year === undefined) {

        var date = new Date();
        year = date.getFullYear();
    }

    this.manufacturer = manufacturer;
    this.model = model;
    this.year = year;

    this.toString = print;
    this.getInfo = print;

    function print(opt) {
        
        var priceStr = '',
            jpy = 0.4119,
            eur = 39.6028;

        if(opt && opt.price == 'rub' && this.price !== undefined) {
            
            var price = parseFloat(this.price.substr(1));

            switch(this.price.substr(0, 1)) {

                case '¥':
                    price *= jpy;
                    break;
                
                case '€':
                    price *= eur;
                    break;
            }

            priceStr = ' (Цена: ' + price.toFixed(2) + ' руб.)';
        }

        return this.manufacturer + ' ' + this.model + ' ' + this.year + priceStr;
    }

    this.getDetailedInfo = function() {
        return 'Производитель: ' + this.manufacturer + '. Модель: ' + this.model + '. Год: ' + this.year;
    }
}

// @TODO: если конструктор вызывается без указания текущего года, то подставлять текущий
// @TODO: реализовать методы вывода информации о машине: 
// console.log('Car: ' + bmw); // Car: BMW X5 2010
// console.log(bmw.getInfo()); // BMW X5 2010
// console.log(bmw.getDetailedInfo()); // Производитель: BMW. Модель: X5. Год: 2010

var bmw    = new Car("BMW", "X5", 2010),
    audi   = new Car("Audi", "Q5", 2012),
    toyota = new Car("Toyota", "Camry");

console.log('Car: ' + bmw);
console.log(bmw.getInfo());
console.log(bmw.getDetailedInfo());

/**
 * Создает экземпляр Автосалона
 * @this {CarDealer}
 * @param {string} name Название автосалона
 */
function CarDealer(name) {
    
    this.name = name;
    this.cars = [];

    this.add = function() {

        for(var a = 0; a < arguments.length; a++)
            this.cars.push(arguments[a]);

        return this;
    }
}

var yandex = new CarDealer('Яндекс.Авто');

yandex
    .add(toyota)
    .add(bmw, audi);

console.log(yandex.cars);

// @TODO: реализовать метод добавления машин в автосалон. Предусмотреть возможность добавления одной машины, нескольких машин.
// yandex
//     .add(toyouta)
//     .add(bmw, audi);

// @TODO: реализовать метод установки цены на машину
/**
 * Установить цену на машину
 * @param {string} car идентификатор машины
 * @param {string} price стоимость
 */
// идентификатор машины составляется следующим образом "производитель модель год"
// стоимость машины может быть задана в двух валютах: йена и евро.
// yandex
//     .setPrice('BMW X5 2010', '€2000')
//     .setPrice('Audi Q5 2012', '€3000')
//     .setPrice('Toyota Camry 2012', '¥3000');

/**
 * Установить цену на машину
 * @param {string} car идентификатор машины
 * @param {string} price стоимость
 */

Object.prototype.setPrice = function(car, price) {

    var carInfo = car.split(' '),
        manufacturer = carInfo[0],
        model        = carInfo[1],
        year         = carInfo[2];

    for(var c = 0; c < this.cars.length; c++) {

        if(
            this.cars[c].manufacturer == manufacturer &&
            this.cars[c].model == model &&
            this.cars[c].year == year
        )
            this.cars[c].price = price;
    }

    return this;
 };

yandex
    .setPrice('BMW X5 2010', '€2000')
    .setPrice('Audi Q5 2012', '€3000')
    .setPrice('Toyota Camry 2012', '¥3000');

console.log(yandex.cars);

// @TODO: реализовать вывод списка автомобилей в продаже, с фильтрацией по стране производителю, используя метод getCountry:
function getCountry() {

    switch(this.manufacturer.toLowerCase()) {
        
        case 'bmw':
        case 'audi':
            return 'Germany';

        case 'toyota':
            return 'Japan';
    }
}

Object.prototype.list = function(opt) {

    var list = [];

    for(var c = 0; c < this.cars.length; c++)
        list.push(this.cars[c].getInfo(opt));

    console.log(list.join(', '));
};

yandex.list();
yandex.list({price: 'rub'});

Object.prototype.listByCountry = function(country, opt) {

    var list = [];

    for(var c = 0; c < this.cars.length; c++)
        if(country == getCountry.apply(this.cars[c]))
            list.push(this.cars[c].getInfo(opt));

    console.log(list.join(', '));
}

yandex.listByCountry('Germany');
yandex.listByCountry('Germany', {price: 'rub'});

// yandex.list(); //BMW X5 2010, Audi Q5 2012, Toyota Camry 2012
// yandex.listByCountry('Germany'); //BMW X5 2010, Audi Q5 2012

// @TODO: бонус! выводить список машин с ценой в рублях.</code></pre></div>

	<h2 id="/tech-txt">Текстовые файлы</h2>
    <p>Для того, чтобы найти все текстовые файлы, в имени которых содержится «yandex», а в содержимом — «школа разработки интерфейсов», я бы использовал следующую команду:</p>
    <div class="info__copy-code"><pre><code class="bash">find . -type f -name *yandex*.txt | xargs grep -ls "школа разработки интерфейсов"</code></pre></div>

	<h2 id="/tech-terminal">Терминал</h2>
    <p>Программы с добавленным verbose-режимом, в котором дополнительно выводится значение каждого их аргументов.</p>
    <p><strong>Bash</strong></p>
    <div class="info__copy-code"><pre><code class="bash">#!/usr/bin/env bash

usage() {
cat << EOF
Usage: printargs.sh [OPTIONS] [ARGUMENTS]
  Print the number of arguments.

OPTIONS:
  -h      print help message
  -m MSG  custom message
  -v      verbose mode

Examples:
  printargs.sh a b c
  printargs.sh -m 'Arguments count: ' a b c
  printargs.sh -h
  printargs.sh -v a b c

EOF
}

while getopts “hvm:” OPTION # TODO: add '-v' option for verbose mode
do
    case $OPTION in
        h)
            usage
            exit 1
            shift;;
        v)
      VERBOSE=true
      shift;;
        m)
            MESSAGE=$OPTARG
            shift;shift;;
    esac
done

COUNT=0

for ARG in $@; do
    # TODO: add '-v' option for verbose mode
    # and print each argument
    
    if [[ "$VERBOSE" ]]; then
      echo $ARG
    fi

    let COUNT+=1
done

if [[ "$MESSAGE" != "" ]]; then
    echo $MESSAGE
fi

echo $COUNT</code></pre></div>
    <p><strong>Python</strong></p>
    <div class="info__copy-code"><pre><code class="python">#!/usr/bin/env python

import argparse

parser = argparse.ArgumentParser(description='Print the number of arguments.')
parser.add_argument('arguments', metavar='ARG', type=str, nargs='*', help='some arguments')
parser.add_argument('-m', dest='message', default='', help='custom message')
parser.add_argument('-v', dest='verbose', default='', help='arguments values')
# TODO: add '-v' option for verbose mode

args = parser.parse_args()

count = 0

for a in args.arguments:
    # TODO: add '-v' option for verbose mode
    # and print each argument
    count += 1
    if args.verbose:
        print(a)

if args.message != '':
    print(args.message)

print(count)</code></pre></div>
	
    <h2 id="/tech-other">Объёмы и обстоятельства</h2>
    <p>Был 2007 год и первый язык программирования, который я изучил — QBasic. Его знание требовалось для вступительных экзаменов в университет. Благодаря этому, сильно заинтересовался программированием и самостоятельно изучал Visual Basic.</p>
    <p>В период обучения в университете, с 2007 по текущий год, поверхностно изучали C++, Java, Delphi, Prolog. Много времени уделили SQL.</p>
    <p>Летом и осенью 2008 года, я занялся подробным изучением С++, однако вскоре переключился на PHP. Которым увлечён и на сегодняшний день в связке с HTML, CSS и JavaScript.</p>
</div>