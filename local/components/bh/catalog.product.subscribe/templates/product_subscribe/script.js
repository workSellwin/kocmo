$(document).ready(function () {

	//оставить предзаказ
	$('#form_prod_popup').submit(function () {
		var data = $(this).serialize();
		$.ajax({
			url: '/ajax/index.php',
			data: data,
			success: function success(response) {
				response = JSON.parse(response);
				if(response['ERROR']){
					$('.subscribe-message').text(response['ERROR']);
					$('.subscribe-message').css('background-color', '#d01c60');
					$('.subscribe-message').show();
				}else{
					$('.subscribe-message').text(response['MESSAGE']);
					$('.subscribe-message').css('background-color', '#049804');
					$('.subscribe-message').show();
				}
			},
		});
		return false
	});

	$('body').on('click', '.js_product__preorder', function () {
		$('.subscribe-message').hide();
	})

});