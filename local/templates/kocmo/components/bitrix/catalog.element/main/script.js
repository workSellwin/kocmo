$(document).ready(function () {
    $('.page-title').remove();
    $('#prod_form').submit(function () {
        var PRODUCT_ID = $('#prod_form input[name=PRODUCT_ID]').val();
        var QUANTITY = $('#prod_form input[name=QUANTITY]').val();
        var this_id = $('input[name=PRODUCT_ID]').val();
        var btm_submit = $(this).find('.product__submit');
        var submit_data_url = btm_submit.attr('data-basket-url');


        if(DATA_OFFERS[this_id]['ADD_BASKET'] == 'N') {

            $.post(
                "/ajax/",
                {
                    ACTION: "addbasket",
                    METHOD: "Add",
                    PARAMS: {
                        'PRODUCT_ID': PRODUCT_ID,
                        'QUANTITY': QUANTITY,
                        'ADD_BASKET': 'Y',
                    },
                },
                onAjaxSuccess
            );
            function onAjaxSuccess(response) {
                if (response) {

                    $('.product__submit .btn-pho').text('Перейти в корзину');
                    DATA_OFFERS[this_id]['ADD_BASKET'] = 'Y';
                    $('.product__counter').hide();
                    btm_submit.attr('data-basket', 'Y');

                    addToBasketTracker(PRODUCT_ID);

                    ajaxContent('header_basket');
                }
            }


        }else{
            location=submit_data_url;
        }

    });



});

function offers($this) {
    var this_id = $($this).attr('id');
    var this_offers = DATA_OFFERS[this_id];

    $('#prod_form input[name=QUANTITY]').val(1);
    $('#prod_form input[name=PRODUCT_ID]').val(this_offers['ID']);

    //встовляем цену офирса
    if ( this_offers['PRICE']['DISCOUNT'] > 0) {
        $('.product__old-price').show();
        $('.product__price').show();
        $('.product__old-price div').text(this_offers['PRICE']['PRICE_OLD']);
        $('.product__price div').text(this_offers['PRICE']['PRICE_NEW']);
        $('.products-item__label').text('-'+this_offers['PRICE']['PERCENT']+'%');
    } else {
        $('.product__old-price').hide();
        $('.product__price').show();
        $('.product__price div').text(this_offers['PRICE']['PRICE_NEW']);
    }

    //переопределение input ADD_BASKET
    if (this_offers['QUANTITY'] <= 0){
        $('#prod_form input[name=ADD_BASKET]').val('N');
    } else {
        $('#prod_form input[name=ADD_BASKET]').val('Y');
    }

    //устоновить id на кнопку submit
    $('.product__submit').attr('data-id', this_offers['ID']);

    //переопределение артикула
    $('.product__view-sku').text('Артикул ' + this_offers['ARTNUMBER']);

    //скрыть, показать элементы в зовисемости от количество товара в оферсе
    if (this_offers['QUANTITY'] > 0 && this_offers['PRICE']['PRICE_NEW'] > 0) {
        $('.product__counter input.counter__input').val(1);
        $('.product__counter input.counter__input').attr('data-max-count', this_offers['QUANTITY']);
        $('.product__submit').show();
        $('.product__preorder').hide();
        $('.product__counter').show();

    } else {
        $('.product__submit').hide();
        $('.product__preorder').show();
        $('.product__counter').hide();

        $('.popup-preorder__product-title').text(this_offers['NAME']);
        $('.popup-preorder__product-description').text(this_offers['PREVIEW_TEXT']);
        $('.popup-preorder__product-sku').text('Артикул: '+ this_offers['ARTNUMBER']);
        $("#form_prod_popup input[name=PARAMS\\[ITEM_ID\\]]").val(this_offers['ID']);

    }

    //изменение текста кнопки 'В корзину'
    if(this_offers['ADD_BASKET'] == 'N' ){
        $('.product__submit .btn-pho').text('В корзину');
        $('div.product__counter').show();
    }else{
        $('.product__submit .btn-pho').text('Перейти в корзину');
        $('div.product__counter').hide();
    }

    //Скрываем выбор количество элементов
    if(this_offers['ADD_BASKET'] == 'N' && this_offers['QUANTITY'] > 0 && this_offers['PRICE']['PRICE_NEW'] > 0){
        $('div.product__counter').show();
    }else{
        $('div.product__counter').hide();
    }

    //Скрываем выбор количество элементов
    if(this_offers['ADD_BASKET'] == 'Y'){
        $('div.product__counter').hide();
    }

    if(this_offers['QUANTITY'] <= 0){
        $('div.product__counter').hide();
    }

    //нет скидки
    if(this_offers['PRICE']['DISCOUNT'] <= 0){
       $('.product__sale-wrap').hide();
    }else{
        $('.product__sale-wrap').show();
    }

    $('.main_js_counter__button').attr('data-offers-id', this_offers['ID']);
    $('.js_counter__input').attr('data-offers-id', this_offers['ID']);



};


function offerDef(PRODUCT_ID) {
    var btm_submit = $(this).find('.product__submit');
    $('.product__submit .btn-pho').text('В карзину');
    DATA_OFFERS[PRODUCT_ID]['ADD_BASKET'] = 'N';
    $('.product__counter').show();
    btm_submit.attr('data-basket', 'N');
}

function changeButton() {
    $('.product__buy div.product__counter').show();
    $('.product__buy button.product__submit .btn-pho').text('В корзину');
}

function clickCounterButtonDetailElement($this) {
    var this_id = $($this).attr('data-offers-id');
    var this_offers = DATA_OFFERS[this_id];

    var $counter = $('body .js_counter__input'),
        maxValue = this_offers['QUANTITY'];


    if ($($this).hasClass('counter__button--up')) {
        var input_val = +$counter.val();

        if (input_val >= maxValue) {
            $counter.val(maxValue);
            $('#prod_form input[name=QUANTITY]').val(maxValue);
        }else{
            $counter.val(input_val + 1);
            $('#prod_form input[name=QUANTITY]').val(input_val + 1);
        }

    } else {
        $counter.val(parseInt($counter.val()) - 1);
        $('#prod_form input[name=QUANTITY]').val(parseInt($counter.val()) - 1);
        if (parseInt($counter.val()) < 1) {
            $counter.val(1);
            $('#prod_form input[name=QUANTITY]').val(1);

        }
    }
}

function keyupCounterButtonDetailElement($this) {
    var this_id = $($this).attr('data-offers-id');
    var this_offers = DATA_OFFERS[this_id];

    var maxValue = this_offers['QUANTITY'];

    var value = parseInt($($this).val());

    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }

    $($this).val($($this).val().replace(/\D/g, ''));

    if (value <= 0) {
        $($this).val('1');
        $('#prod_form input[name=QUANTITY]').val(1);
    } else if (value > maxValue) {
        $($this).val(maxValue);
        $('#prod_form input[name=QUANTITY]').val(maxValue);
    }else{
        $('#prod_form input[name=QUANTITY]').val(value);
    }

}
