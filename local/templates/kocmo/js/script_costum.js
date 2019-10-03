$(document).ready(function () {
    //Подгрузка товаров на главной
    $('body').on('click', '.js_my_btn_suggestions', function () {
        console.log('324523452345');



        $(function () {
            MainJs.init({
                fancyboxLink: 'a.fancybox',
                fullWidthSlider: '.js_full-width-slider',
                suggestionMoreBtn: '.js_suggestions__btn',
                mobileScrollSlider: '.js_mobile-scroll-slider',
                scrollSlider: '.js_scroll-slider',
                salesSlider: '.js_sales-slider__swiper-container',
                tabWrapper: '.js_tabs-wrap',
                tabBtn: '.js_tab',
                tabInner: '.js_panel'
            });
        });
    })
});
