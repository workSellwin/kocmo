$(document).ready(function () {

    $('body').on('click', '.js_click_span .counter__button--down', function () {
        var input = $(this).parent('.js_click_span').find('.js_change_input');
        var count = input.val();
        var newCount = parseInt(count) - 1;
        if (newCount != 0) {
            input.val(newCount);
            input.trigger('change');
        }
    });

    $('body').on('click', '.js_click_span .counter__button--up', function () {
        var input = $(this).parent('.js_click_span').find('.js_change_input');
        var max = input.data('max-count');
        var count = input.val();
        var newCount = parseInt(count) + 1;
        if (newCount <= parseInt(max)) {
            input.val(newCount);
            input.trigger('change');
        } else {
            alert('Максимум  = ' + max);
        }
    });

    $('body').on('change', '.js_click_span .js_change_input', function () {
        var input = $(this);
        var max = input.data('max-count');
        var count = input.val();
        if (count > parseInt(max)) {
            count = max;
            input.val(count);
        }
        var id = input.data('id');
        var price = parseFloat(input.data('price'));
        var discount = parseFloat(input.data('discount'));
        var price_contener = $(this).parents('.basket__item-details-wrap').find('.basket__item-price-wrap');
        if (price) {
            price_contener.find('.basket__item-old-price').html(formatPrice(price * count) + ' <span>руб</span>');
        }
        if (discount) {
            price_contener.find('.basket__item-price').html(formatPrice(discount * count) + ' <span>руб</span>');
        }
        UpdateBasketCountItem(id, count);
        BlockPrice();
    });

    function UpdateBasketCountItem(id, count) {
        $.post(
            "/ajax/",
            {
                ACTION: "updatebasket",
                METHOD: "Update",
                PARAMS: {
                    'PRODUCT_ID': id,
                    'UPDATE_BASKET': 'Y',
                    'FIELDS': {
                        'QUANTITY': count,
                    },
                },
            },
        );
    }

    function formatPrice(price) {
        return price.toFixed(2);
    }

    function BlockPrice() {
        ajaxContent('ajax_basket_price_container');
    }


});
