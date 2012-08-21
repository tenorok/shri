<div id="fotorama">
    <a href="big.jpg"><img src="small.jpg"></a> //С превью
    <img src="2.jpg">
    <img src="3.jpg">
</div>

<script type="text/javascript">
    $(function() {
        
        //Инициализация фоторамы
        $('#fotorama').fotorama();

		// Или можно просто добавить class="fotorama_auto"
		<div class="fotorama_auto">
			<img src="1.jpg> <img src="2.jpg"> <img src="3.jpg">
		</div>
        
        //Изменение размера области фоторамы
        $('#fotorama').trigger('rescale', [400, 200]);
        
        //Полноэкранный режим
        $('#fotorama').trigger('rescale', [$(window).width(), $(window).height()]);
        
        //Перелистывание изображений по любому другому событию на странице
        $('#aaa').click(function() {
            $('#fotorama').trigger('showimg', i); //Где i - номер изображения
        });
        
        ---------------------------------------------------------------------------------
        //Примеры опций:
        
        //Инициализация с отключением нижней полосы превьюшек
        $('#fotorama').fotorama({ thumbsPreview: false });
        
        //Отключение перетаскивания (dragging and flicking)
        $('#fotorama').fotorama({ touchStyle: false });
        
        //Пример использования нескольких опций
        $('#fotorama').fotorama({
            backgroundColor: '#797775',
            arrowsColor: 'yellow'
        });
        
        Полный список доступных опций:
            data — массив, в котором можно указать данные фотографий для инициализации (type: array, default: null);
            Пример:
                <div id="fotorama">
                    To view the gallery please enable a browser feature called Javascript.
                </div>
                
                $('#fotorama').fotorama({
                    data: [
                        {img: 'http://fotoramajs.com/;-)/03.jpg',
                        thumb: 'http://fotoramajs.com/;-)/th/03.jpg',
                        caption: 'Sasha'},    
                        {img: 'http://fotoramajs.com/;-)/05.jpg',
                        thumb: 'http://fotoramajs.com/;-)/th/05.jpg',
                        caption: 'Masha'}
                    ],  
                    width: 700,
                    height: 467,
                    resize: true,
                    caption: true
                });
            
            width — ширина контейнера с фотографиями (type: number, default: null);
            
            height — высота контейнера с фотографиями (type: number, default: null);
            
            startImg — индекс фотографии, которая будет показана при загрузке галереи, 0 — 1-я, 1 — 2-я и т. д. (type: number, default: 0);
            
            transitionDuration – время прохода анимаций в миллисекундах (type: number, default: 333);
            
            touchStyle — флаг, включающий возможность перетаскивать фотографии мышкой на компьютере или пальцем на мобильных устройствах (type: boolean, default: true);
            
            pseudoClick — флаг, разрешающий переход между фотографиями по клику, при включённом тач-стиле (touchStyle) интерфейса (type: boolean, default: true);
            
            pseudoClickLoop — флаг, закольцовывающий фотографии по псевдо-клику (pseudoClick) (type: boolean, default: false);
            
            backgroundColor — цвет фона под фотографиями, переопределяет значение из CSS (type: string, default: null);
            
            margin — размер горизонтальных отступов между фотографиями (type: number, default: 5);
            
            minPadding — минимальный размер внутренних полей для контейнера с фотографиями, когда соотношение сторон активной фотографии не совпадает с соотношением сторон контейнера (type: number, default: 10);
            
            alwaysPadding — флаг, добавляющий внутренние поля для контейнера с фотографиями при любом соотношении сторон активной фотографии (type: boolean, default: false);
            
            preload — количество предзагружаемых фотографий с каждого края активной фотографии, применяется только, когда указаны отдельные файлы превьюшек, в противном случае опция игнорируется и все фотографии загружаются сразу (type: number, default: 3);
            
            resize — флаг, включающий «резиновость», Фоторама будет занимать всю ширину родительского блока, но при этом вписываться в окно по высоте (type: boolean, default: false);
            
            zoomToFit — флаг, управляющий растягиванием фотографий, размер которых меньше размера контейнера (type: boolean, default: true);
            
            cropToFit — флаг, включающий такое растягивание и обрезание фотографий, при котором они занимают всю площадь Фоторамы (type: boolean, default: false);
            
            vertical — флаг, при включении которого, фотографии и превьюшки будут выстраиваться не горизонтально, а вертикально (type: boolean, default: false);
            
            verticalThumbsRight — флаг, ставящий ленту превьюшек справа при включённом вертикальном режиме (vertical), по умолчанию в вертикальном режиме превьюшки слева (type: boolean, default: false);
            
            arrows — флаг, включающий навигационные стрелки над фотографиями (type: boolean, default: true);
            
            arrowsColor — цвет стрелок над фотографиями, переопределяет значение из CSS (type: string, default: null);
            
            thumbs — флаг, с помощью которого можно включить или отключить блок с превьюшками или точками-индикаторами целиком (type: boolean, default: true);
            
            thumbsBackgroundColor — цвет фона блока с превьюшками или точками-индикаторами, переопределяет значение из CSS (type: string, default: null);
            
            thumbColor — цвет точек-индикаторов, переопределяет значение из CSS (type: string, default: null);
            
            thumbsPreview — флаг, который включает превьюшки и отключает точки-индикаторы (type: boolean, default: true);
            
            thumbSize — высота превьюшек при горизонтальном (дефолтном) режиме Фоторамы или ширина — при вертикальном, если не менять значение, то высота превьюшек при горизонтальном режиме будет 48px, ширина при вертикальном — 64px, размер второй стороны находится автоматически (type: number, default: null);
            
            thumbMargin — размер внешних полей превьюшек (type: number, default: 5);
            
            thumbBorderWidth — толщина рамки вокруг активной превьюшки (type: number, default: 3);
            
            thumbBorderColor — цвет рамки вокруг активной превьюшки, переопределяет значение из CSS (type: string, default: null);
            
            caption — флаг, включающий отображение подписей к фотографиям, которые можно задать через атрибут alt (type: boolean, default: false);
            
            html — массив, содержащий блоки или непосредственно HTML для внедрения в Фотораму (type: array, default: null);
            Пример:
                <div id="html" style="display: none;">
                    <div class="b-slide" style="top: 10px; right: 45px; width: 35%;">
                        <h2>Стиль</h2>
                        <p>Cогласно традиционным представлениям, стиль редуцирует <em>латентный голос</em> персонажа</p>
                    </div>
                    <div class="b-slide b-slide_black" style="top: 10px; left: 45px; width: 35%;">
                        <p>Кластерное вибрато:</p>
                        <ol>
                            <li>так или иначе многопланово;</li>
                            <li>трансформирует литературный гармонический интервал;</li>
                            <li>тем не менее узус никак не предполагал здесь родительного падежа.</li>
                        </ol>
                    </div>
                    <div class="b-slide">
                        <h2>Естественный катарсис</h2>
                        <p>Моя экзистенциальная тоска выступает как <a href="http://ru.wikipedia.org/wiki/Мотив_(психология)" class="white">побудительный мотив</a> творчества</p>
                    </div>
                </div>
                
                var html = $('.b-slide', '#html');
                
                $('#fotorama').fotorama({
                    width: 700,
                    height: 467,
                    resize: true,
                    html: {
                        0: html.eq(0),
                        1: html.eq(2),
                        2: html.eq(1)
                    }
                });
            
            onShowImg — функция, которая будет выполнена при смене активной фотографии (type: function, default: null);
            Пример:
                var console = $('#fotorama-console');
                
                $('#fotorama').fotorama({
                    onShowImg: function(data) {
                        console.html(
                            'Index of active photo: ' + data.index
                        )
                    },    
                    width: 700,
                    height: 467,
                    resize: true
                });
            
            shadows — флаг, включающий тени в интерфейсе (type: boolean, default: true);
            
            detachSiblings — флаг, включающий удаление из DOM всех фотографий, кроме активной и предзагруженных вокруг неё (для повышения производительности на мобильных устройствах и слабых компьютерах) (type: boolean, default: true).
    });
</script>