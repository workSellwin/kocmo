$(document).ready(function () {
    //ОБЪЕКТ СО ВСЕМИ ITEM (OBJ_ITEMS)
    if (typeof OBJ_ITEMS != 'undefined') {
        initBtnItem(OBJ_ITEMS);
    }
});

//сливает два объекта вместе
function mergeJsObj(OBJ_ITEMS, AJAX_ITEMS) {
    OBJ_ITEMS = Object.assign(OBJ_ITEMS, AJAX_ITEMS);
    return OBJ_ITEMS;
}

function initBtnItem(OBJ_ITEMS) {
    if (typeof OBJ_ITEMS != 'undefined') {
        for (ITEM in OBJ_ITEMS) {
            btnItem(ITEM);
        }
    }
}

//изменяет кнопку у продукта (item)
function btnItem(PRODUCT_ID) {
    if (OBJ_ITEMS[PRODUCT_ID]) {
        var item = OBJ_ITEMS[PRODUCT_ID];
        var btn = $('.prod-items-id_' + PRODUCT_ID);
        //лежит ли в карзине товар
        if (item['IS_BASKET'] == 'Y' && item['IS_OFFERS'] != 'Y') {
            btn.addClass('active_btn_basket');
        } else {
            btn.removeClass('active_btn_basket');
        }
        //изменение текста кнопки
        $('.prod-items-id_' + PRODUCT_ID + ' span').text(item['BTN_TEXT']);

    } else {
        console.log('Нет item с таким ID!!!')
    }
}

//Добавить продукт в карзину
function productsItemAdd(PRODUCT_ID) {
    if (OBJ_ITEMS[PRODUCT_ID]) {
        var item = OBJ_ITEMS[PRODUCT_ID];
    }
    if (item['IS_OFFERS'] == 'Y') {
        location = item['URL_DETAIL'];
    }
    if (item['QUANTITY'] <= 0) {
        location = item['URL_DETAIL'];
    } else {
        if (item['IS_BASKET'] == 'N') {
            $.post(
                "/ajax/",
                {
                    ACTION: "addbasket",
                    METHOD: "Add",
                    PARAMS: {
                        'PRODUCT_ID': PRODUCT_ID,
                        'QUANTITY': 1,
                        'ADD_BASKET': 'Y',
                    },
                },
                onAjaxSuccess
            );

            function onAjaxSuccess(response) {
                if (response) {
                    item['IS_BASKET'] = 'Y';
                    item['BTN_TEXT'] = 'Перейти в корзину';
                    btnItem(PRODUCT_ID);
                    addToBasketTracker(PRODUCT_ID);
                    ajaxContent('header_basket');
                    ReloadAjax();
                }
            }
        } else {
            location = item['URL_CART'];
        }
    }
}

//Удалить продукт из маленькой карзины
function productsItemDel(PRODUCT_ID, ID) {
    var item = false;
    if (typeof OBJ_ITEMS != 'undefined') {
        if (typeof OBJ_ITEMS[PRODUCT_ID] != 'undefined') {
            item = OBJ_ITEMS[PRODUCT_ID];
        }

    }
    $.post(
        "/ajax/",
        {
            ACTION: "delbasket",
            METHOD: "Del",
            PARAMS: {
                'PRODUCT_ID': PRODUCT_ID,
                'ID': ID,
                'DEL_BASKET': 'Y',
            },
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        if (response) {
            if (item != '') {
                item['IS_BASKET'] = 'N';
                item['BTN_TEXT'] = 'В корзину';
                btnItem(PRODUCT_ID);
            }
            if (typeof offerDef != 'undefined') {
                offerDef(PRODUCT_ID);
            }
            ajaxContent('header_basket_count');
            ajaxContent('header_basket_content');
            ReloadAjax();
        }
    }
}


//Удалить продукт из карзины на странице cart
function BigBasketItemDel(PRODUCT_ID, ID) {

    var item = false;
    if (typeof OBJ_ITEMS != 'undefined') {
        if (typeof OBJ_ITEMS[PRODUCT_ID] != 'undefined') {
            item = OBJ_ITEMS[PRODUCT_ID];
        }
    }

    $.post(
        "/ajax/",
        {
            ACTION: "basket",
            METHOD: "Del",
            PARAMS: {
                'PRODUCT_ID': PRODUCT_ID,
                'ID': ID,
                'DEL_BASKET': 'Y',
            },
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        if (response) {
            if (item != '') {
                item['IS_BASKET'] = 'N';
                item['BTN_TEXT'] = 'В корзину';
            }

            var flag = 0;
            $('.basket_item_container .basket__item').each(function () {
                flag++;
            });

            if(flag >=1){
                //ajaxContent('ajax_basket_item_container');
                ajaxContent('ajax_basket_price_container');
                ReloadAjax();
            }else{
                location="/catalog/"
            }
        }
    }

}

// обновление количества товара в карзине
function UpdateProductBasket(PRODUCT_ID, QUANTITY) {
    setTimeout(function () {
        $.post(
            "/ajax/",
            {
                ACTION: "updatebasket",
                METHOD: "Update",
                PARAMS: {
                    'PRODUCT_ID': PRODUCT_ID,
                    'UPDATE_BASKET': 'Y',
                    'FIELDS': {
                        'QUANTITY': QUANTITY,
                    },
                },
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(response) {
            if (response) {
                ajaxContent('header_basket_count');
                ajaxContent('header_basket_content');
                ajaxContent('ajax_basket_item_container');
                ajaxContent('ajax_basket_price_container');
            }
        }
    }, 500);
}

//встовляет контейнер по ID
function ajaxContent($CONTENT_ID) {
    var url = window.location.href;
    $.post(
        url,
        {
            ACTION: 'ajax',
            CONTENT_ID: $CONTENT_ID,
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        $('#' + $CONTENT_ID).html(response);
    }
}

//трекинг для модуля Retail Rocket
function addToBasketTracker(productID) {
    rrApi.addToBasket(productID);
}

//трекинг для модуля Retail Rocket
function transactionTracker(obj_product) {
    var items = [];
    var transaction_id = getRandomInt(999999999999, 9999999999999);
    for (variable in obj_product) {
        var item = {
            id: obj_product[variable]['PRODUCT_ID'],
            qnt: obj_product[variable]['QUANTITY'],
            price: obj_product[variable]['BASE_PRICE']
        };
        items.push(item);
    }
}

//получить рендомное число в диопазоне
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min; //Максимум не включается, минимум включается
}

//init js
function ReloadAjax() {
    $(document).ready(function () {
        MainJs.setMaxHeights($('.products__container .products-item__description'));
        MainJs.counterInit();
        MainJs.filterDropDownInit();
        MainJs.headerBasketScrollInit();
    });
}

//проверяет есть ли элементы в карзине
function EmptyBasket() {
    $.post(
        "/ajax/",
        {
            ACTION: "basket",
            METHOD: "EmptyBasket",
            PARAMS: {'IS_PRODUCT': 'Y'},
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        return response;
    }
}

// нажатие на кнопки + -
function clickPlusMinusCounterButton($this, ID) {
    var this_btn = $($this);
    var input = this_btn.parent().children('input');
    var maxValue = input.attr('data-max-count');
    var input_val = input.val();

    if (this_btn.hasClass('counter__button--up')) {
        if (input_val >= maxValue) {
            input.val(maxValue);
            alert('Максимальное количество товара '+maxValue);
        } else {
            input_val++;
            input.val(input_val);
            UpdateProductBasket(ID, input_val);
        }
    }
    if (this_btn.hasClass('counter__button--down')) {
        if (input_val <= 1) {
            input.val(1);
        } else {
            input_val--;
            input.val(input_val);
            UpdateProductBasket(ID, input_val);
        }
    }
}

// ввод количества товара
function keyupCounterButton($this, ID) {
    var maxValue = $($this).attr('data-max-count');
    var value = parseInt($($this).val());

    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }

    $($this).val($($this).val().replace(/\D/g, '1'));

    if (value <= 0) {
        $($this).val('1');
        UpdateProductBasket(ID, 1);
    } else if (value > maxValue) {
        $($this).val(maxValue);
        UpdateProductBasket(ID, maxValue);
    } else {
        UpdateProductBasket(ID, value);
    }
}