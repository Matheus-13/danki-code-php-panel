$(function(){
	$('body').on('submit', 'form.ajax-form', function() {
		var form = $(this);
		$.ajax({
			beforeSend: function() {
				$('.overlay-loading').fadeIn();
			},
			url: include_path + 'page/form.php',
			method: 'post',
			dataType: 'json',
			data: form.serialize()
		}).done(function(data) {
			$('.overlay-loading').fadeOut();
			if (data.success) {
				$('.sucesso').slideToggle();
				setTimeout(function() {
					$('.sucesso').fadeOut();
				}, 3000)
			} else {}
		});
		return false;
	})
})