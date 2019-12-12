<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="main-content">
    <div class="shops">
        <div class="shops__header">
            <div class="shops__header-select">
                <div class="form-field">
                    <select name="cities" class="js_custom-select js_set-position">
                        <option value="all" data-lat="53.62891183780857" data-lng="27.539946343749957"
                                data-zoom="6">Все
                            магазины
                        </option>
                        <?foreach ($arResult['CITY'] as $city):
                            if($arResult['THIS_CITY'][$city['ID']]):?>
                                <option value="<?=$city['ID']?>"  data-lat="<?=$city['PROPERTY_LAT_VALUE']?>" data-lng="<?=$city['PROPERTY_LNG_VALUE']?>"
                                        data-zoom="<?=$city['PROPERTY_ZOOM_VALUE']?>"><?=$city['NAME']?>
                                </option>
                            <?endif;?>
                        <?endforeach;?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="shops__map-wrap">
        <div id="map" class="shops__map"></div>
        <div class="shops__map-controls">
            <div class="shops__map-controls-zoom">
                <div class="shops__map-controls-zoom-in shops__map-controls-zoom-item js_map-zoom-in"></div>
                <div class="shops__map-controls-zoom-out shops__map-controls-zoom-item js_map-zoom-out"></div>
            </div>

            <div class="shops__map-controls-loc js_map-zoom-loc">
                <svg width="10" height="10">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-map-loc"></use>
                </svg>
            </div>
        </div>
    </div>

    <div class="shops__info preloader-wrap">
        <div class="shops__info-inner">

            <?foreach ($arResult['DATA'] as $magaz):?>

                <div class="shops__item" data-city-id="<?=$magaz['CITY_ID']?>">
                    <div class="shops__item-img">
                        <img src="<?=$magaz['PREVIEW_PICTURE']?>" alt="">
                    </div>

                    <div class="shops__item-content-wrap">
                        <div class="shops__item-content">
                            <div class="shops__item-title"><?=$magaz['NAME']?></div>
                            <span class="shops__item-address js_set-position"
                                  data-lat="<?=$magaz['LAN']?>"
                                  data-lng="<?=$magaz['LNG']?>"
                                  data-zoom="16"><?=$magaz['ADDRESS']?></span>
                        </div>

                        <div class="shops__item-schedule">
                            <div class="shops__item-schedule-title">Время работы:</div>
                            <div class="shops__item-schedule-hours"><?=$magaz['WORK_HOUR']?></div>
                        </div>
                    </div>
                </div>

            <?endforeach;?>

        </div>

        <div class="preloader" style="display: none;">
            <svg width="64" height="64">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preloader"></use>
            </svg>
        </div>
    </div>


</div>


<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script>

    $('select.js_custom-select').on('change', function(){
        var city_id = $(this).val();
        console.log(city_id);
        $("div.shops__item").each(function(){
            var data_city_id = $(this).attr('data-city-id');
            if(city_id == 'all'){
                $(this).show();
            }else if(city_id == data_city_id){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });



    ymaps.ready(function () {

        var myMap = window.map = new ymaps.Map('map', {
                center: [53.62891183780857, 27.539946343749957],
                zoom: 6,
                behaviors: ['default', 'scrollZoom'],
                controls: []
            }),
            /**
             * Создадим кластеризатор, вызвав функцию-конструктор.
             * Список всех опций доступен в документации.
             * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Clusterer.xml#constructor-summary
             */
            clusterer = new ymaps.Clusterer({
                /**
                 * Через кластеризатор можно указать только стили кластеров,
                 * стили для меток нужно назначать каждой метке отдельно.
                 * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/option.presetStorage.xml
                 */
                preset: 'twirl#invertedVioletClusterIcons',
                /**
                 * Ставим true, если хотим кластеризовать только точки с одинаковыми координатами.
                 */
                groupByCoordinates: false,
                /**
                 * Опции кластеров указываем в кластеризаторе с префиксом "cluster".
                 * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Cluster.xml
                 */
                clusterDisableClickZoom: true,

                // Зададим массив, описывающий иконки кластеров разного размера.
                clusterIcons: [{
                    href: '<?=KOCMO_TEMPLATE_PATH?>/images/map-loc.png',
                    size: [48, 66],
                    offset: [-24, -33]
                }]
            }),
            /**
             * Функция возвращает объект-данных для метки.
             * Поле данных clusterCaption будет отображено в списке геообъектов в балуне кластера.
             * Поле balloonContentBody - источник данных для контента балуна.
             * Оба поля поддерживают HTML-разметку.
             * Список полей данных, которые используют стандартные макеты содержимого иконки метки
             * и балуна геообъектов, можно посмотреть в документации.
             * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/GeoObject.xml
             */
            getPointData = function (shopInfo) {
                return {
                    balloonContentBody: '<div class="shops__balloon">' + shopInfo.schedule + '</div>',
                    clusterCaption: '<div class="shops__caption">' + shopInfo.name + '</div>',
                    balloonContentHeader: '<div class="shops__caption">' + shopInfo.name + '</div>',
                    // balloonContent: '<div class="shops__balloon">' + shopInfo.schedule + '</div>'
                };
            },
            /**
             * Функция возвращает объект-опций для метки.
             * Все опции, которые поддерживают геообъекты можно посмотреть в документации.
             * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/GeoObject.xml balloonIconImageHref: 'assets/images/map-loc.png'
             */
            getPointOptions = function (obj) {
                return {
                    // Необходимо указать данный тип макета.
                    iconLayout: 'default#image',
                    // Своё изображение иконки метки.
                    iconImageHref: '<?=KOCMO_TEMPLATE_PATH?>/images/map-loc.png',
                    // Размеры метки.
                    iconImageSize: [48, 66],
                    // Смещение левого верхнего угла иконки относительно
                    // её "ножки" (точки привязки).
                    iconImageOffset: [-24, -33]
                };
            },

            pointsObj = <?echo CUtil::PhpToJSObject($arResult['DATA_OBJ'])?>,

            geoObjects = [];

        //отключение зума
        myMap.behaviors.disable('scrollZoom');

        /**
         * Данные передаются вторым параметром в конструктор метки, опции третьим.
         * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Placemark.xml#constructor-summary
         */
        for (var i = 0, len = pointsObj.length; i < len; i++) {
            geoObjects[i] = new ymaps.Placemark(pointsObj[i].position, getPointData(pointsObj[i]), getPointOptions());
            /**
             * Так же их можно добавлять/менять динамически после создания меток.
             * geoObjects[i].properties.set(getPointData(i));
             * geoObjects[i].options.set(getPointOptions());
             */
        }

        /**
         * Так же можно менять опции кластеризатора.
         */
        clusterer.options.set({
            gridSize: 80,
            clusterDisableClickZoom: true
        });

        /**
         * В кластеризатор можно добавить javascript-массив меток (не геоколлекцию) или одну метку.
         * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Clusterer.xml#add
         */
        clusterer.add(geoObjects);

        /**
         * Поскольку кластеры добавляются асинхронно,
         * дождемся их добавления, чтобы выставить карте область, которую они занимают.
         * Используем метод once чтобы сделать это один раз.
         */
        clusterer.events.once('objectsaddtomap', function () {
            myMap.setBounds(clusterer.getBounds());
        });

        /**
         * Кластеризатор, расширяет коллекцию, что позволяет использовать один обработчик
         * для обработки событий всех геообъектов.
         * Выведем текущий геообъект, на который навели курсор, поверх остальных.
         */
        clusterer.events
        // Можно слушать сразу несколько событий, указывая их имена в массиве.
            .add(['mouseenter', 'mouseleave'], function (e) {
                var target = e.get('target'), // Геообъект - источник события.
                    eType = e.get('type'), // Тип события.
                    zIndex = Number(eType === 'mouseenter') * 1000; // 1000 или 0 в зависимости от типа события.

                target.options.set('zIndex', zIndex);
            });

        /**
         * После добавления массива геообъектов в кластеризатор,
         * работать с геообъектами можно, имея ссылку на этот массив.
         */
        clusterer.events.add('objectsaddtomap', function () {
            for (var i = 0, len = geoObjects.length; i < len; i++) {
                var geoObject = geoObjects[i],
                    /**
                     * Информацию о текущем состоянии геообъекта, добавленного в кластеризатор,
                     * а также ссылку на кластер, в который добавлен геообъект, можно получить с помощью метода getObjectState.
                     * @see http://api.yandex.ru/maps/doc/jsapi/2.x/ref/reference/Clusterer.xml#getObjectState
                     */
                    geoObjectState = clusterer.getObjectState(geoObject),
                    // признак, указывающий, находится ли объект в видимой области карты
                    isShown = geoObjectState.isShown,
                    // признак, указывающий, попал ли объект в состав кластера
                    isClustered = geoObjectState.isClustered,
                    // ссылка на кластер, в который добавлен объект
                    cluster = geoObjectState.cluster;

                if (window.console) {
                    console.log('Геообъект: %s, находится в видимой области карты: %s, в составе кластера: %s', i, isShown, isClustered);
                }
            }
        });

        myMap.geoObjects.add(clusterer);

        $(document).on('click change', '.js_set-position', function (e) {
            var isSelect = e.target.tagName === 'SELECT',
                $el = isSelect ? $("option:selected", this) : $(this),
                position = [$el.data('lat'), $el.data('lng')],
                zoom = parseInt($el.data('zoom')) || 10,
                scrollTop = $('#map').offset().top - $('.header').height();

            if (!isSelect) {
                $('body, html').animate({scrollTop: scrollTop}, 500);
            }

            myMap.setZoom(zoom);
            myMap.setCenter(position);
        });

        $('.js_map-zoom-loc').on('click', function () {
            ymaps.geolocation.get({
                provider: 'yandex',
                mapStateAutoApply: true,
                autoReverseGeocode: false
            }).then(function (result) {
                // Красным цветом пометим положение, вычисленное через ip.

                result.geoObjects.get(0).properties.set({});

                myMap.geoObjects.add(result.geoObjects);
            });
        });

        $('.js_map-zoom-in').on('click', function () {
            myMap.setZoom(myMap.getZoom() + 1);
        });

        $('.js_map-zoom-out').on('click', function () {
            myMap.setZoom(myMap.getZoom() - 1);
        });
    });
</script>
<!-- /yandex map -->
