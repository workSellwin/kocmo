function changeSmartFilter($this) {
    console.log($this);
    var data = $('#ajax_filter').serialize();
    var url = document.location.pathname + "?ajax=y&" + data;
    NumberElements(data, url);
}

function ajaxSmartFilter(url) {
    $.post(
        url,
        {
            ajax_filter: "Y",
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data)
    {
        //console.log(data);
        var $preloader = $('#AJAX_FILTER_CONTAINER');
        $preloader.addClass('preloader--active');
        $('#AJAX_FILTER_CONTAINER').html(data);
        $preloader.removeClass('preloader--active');
        MainJs.setMaxHeights($('.products__container .products-item__description'));
    }
}

function NumberElements(data, url) {
    BX.ajax.loadJSON(
        url,
        data,
        function (dt) {
            var res = dt['FILTER_AJAX_URL'].replace(/&amp;/g,'&');
            ajaxSmartFilter(res);
            history.pushState(null, null, res)
        },
    );
}

function checkCurr(d) {
    if(window.event)
    {
        if(event.keyCode == 37 || event.keyCode == 39) return;
    }
    d.value = d.value.replace(/\D/g,'');

    changeSmartFilter(true);
}