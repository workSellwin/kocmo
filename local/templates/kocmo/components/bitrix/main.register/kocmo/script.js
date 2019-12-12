$(function () {
    $('main').on('change', '.form-register [name="REGISTER[EMAIL]"]', function () {
        $('.form-register [name="REGISTER[LOGIN]"]').val($(this).val());
    });
    $('main').on('change', '.form-register [name="REGISTER[PHONE_NUMBER]"]', function () {
        $('.form-register [name="REGISTER[PERSONAL_PHONE]"]').val($(this).val());
    });
    $('.form-register [name="REGISTER[PHONE_NUMBER]"]').mask("+375(99) 999-99-99");
    //$('.form-register [name="REGISTER[EMAIL]"]').mask("*****@******");
});
