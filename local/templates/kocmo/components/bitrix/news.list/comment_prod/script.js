$(document).ready(function(){
    $('body').on('click', '.js_reviews_comment__btn', function () {

        var count_page = +$('#AJAX_COMMENT_CONTAINER').attr('data-NavPageCount');
        var page = +$('#AJAX_COMMENT_CONTAINER').attr('data-Page');
        var url = $(this).attr('data-url');
        if (page <= count_page) {

            url = url + (page + 1);

            $.post(
                url,
                {
                    ajax_comment: "Y",
                },
                onAjaxSuccess
            );

            function onAjaxSuccess(data) {

                $('#AJAX_COMMENT_CONTAINER').append(data);
                $('#AJAX_COMMENT_CONTAINER').attr('data-Page', (page + 1));


                if ($('#AJAX_COMMENT_CONTAINER').attr('data-Page') >= count_page) {
                    $('.js_reviews_comment__btn').remove();
                }
            }
        }
    });

    $('body').on('change', '.js_custom-filter-select', function () {
        var name = $(this).attr('NAME');
        var val = $(this).val();
        location=window.location.pathname+'?'+name+'='+val+'#reviews';
    });



});