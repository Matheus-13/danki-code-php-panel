var PATH = $('constants').attr('path');
var PATH_PANEL = $('constants').attr('pathpanel');
var PAGE = '';

function showLoading() {
	$('.loading, .loadingBox').fadeIn(200);
}

function hideLoading() {
	$('.loading, .loadingBox').fadeOut(200);
}

function message(type, message) {
	if (type == 'success') {
		$('#content').prepend("<div class='alert success'><i class='fa fa-check'></i> "+ message +"</div>");

	} else if (type == 'error') {
		$('#content').prepend("<div class='alert error'><i class='fa fa-times'></i> "+ message +"</div>");

	} else if (type == 'warning') {
		$('#content').prepend("<div class='alert warning'><i class='fa fa-warning'></i> "+ message +"</div>");
	}
}

function getChat(){
	$.ajax({
		method: 'post',
		url: PATH_PANEL + 'ajax/chat.php',
		data: {
			action: 'get'
		}

	}).done(function(data){
		if (data != '') {
			$('.chatBox').append(data);
			$('.chatBox').scrollTop($('.chatBox')[0].scrollHeight);
		}
	});
}

function insertChat(){
	var message = $('textarea').val();

	$.ajax({
		dataType: 'json',
		method: 'post',
		url: PATH_PANEL + 'ajax/chat.php',
		data: {
			action: 'insert',
			message: message
		}

	}).done(function(data){
		if (data.success) $('textarea').val('');
	});
}

function resetChat(){
	var message = $('[name=reset]').attr('alert');
	if (!confirm(message)) return false;

	$.ajax({
		dataType: 'json',
		method: 'post',
		url: PATH_PANEL + 'ajax/chat.php',
		data: {
			action: 'reset'
		}

	}).done(function(data){
		if (data.success)
			$('.chatBox').find('.message').remove();
	});
}

function paidItem(id, el) {
	if (!confirm('Deseja continuar?'))
		return false;

	$.ajax({
		dataType: 'json',
		method: 'post',
		url: PATH_PANEL + 'ajax/paid.php',
		data: {
			id: id
		}

	}).done(function(data){
		$('.alert').remove();
		if (data.success) {
			el.fadeOut();
		} else {
			$('#content').prepend("<div class='alert error'><i class='fa fa-times'></i> "+ data.message +"</div>");
		}
	})
}

function deleteItem(type, id, el) {
	if (!confirm('Deseja excluir o registro?'))
		return false;

	$.ajax({
		dataType: 'json',
		method: 'post',
		url: PATH_PANEL + 'ajax/delete.php',
		data: {
			type: type,
			id: id
		}

	}).done(function(data){
		$('.alert').remove();
		if (data.success) {
			el.fadeOut();
		} else {
			$('#content').prepend("<div class='alert error'><i class='fa fa-times'></i> "+ data.message +"</div>");
		}
	})
}

// Inicializa coisas
function init() {
	PAGE = $('const').attr('page');

	// Esconde a box de alerta
	$('.alert').on('click', function(){
		$('.alert').fadeOut();
	});
	$('.alert').click(function(){
		$('.alert').fadeOut();
	});

	// Confirmação de exclusão
	$('[delete=true]').on('click', function(){
		if (confirm('Deseja excluir o registro?'))
		    return true;
		else
			return false;
	});

	// Exclusão via ajax
	$('[delete=ajax]').on('click', function(e){
		e.preventDefault();
		var id = $(this).attr('id');
		var el = $(this).parent().parent();
		switch(PAGE) {
			case 'customers':
				deleteItem('customer', id, el);
			break;

			case 'products':
				deleteItem('product', id, el);
			break;

			case 'productImgs':
				deleteItem('productImg', id, el);
			break;

			case 'enterprises':
				deleteItem('enterprise', id, el);
			break;

			case 'properties':
				el = $(this).parent().parent().parent();
				deleteItem('property', id, el);
			break;

			case 'propertyImg':
				deleteItem('propertyImg', id, el);
			break;

			case 'schedule':
				el = $(this).parent().parent().parent();
				deleteItem('task', id, el);
			break;
		}
	});

	// Marcar como pago via ajax
	if (PAGE == 'payments') {
		$('[paid=true]').on('click', function(e){
			e.preventDefault();
			var id = $(this).attr('id');
			var el = $(this).parent().parent().parent();
			paidItem(id, el);
		});
	}

	if (PAGE == 'news') {
		tinymce.init({selector:'textarea.editor'});
	}

	if (PAGE == 'enterprises') {
		$('.sortable').sortable({
			start: function(){
				$(this).find('.contactBox').css('border-style', 'dashed');
			},
			update:function(event, ui){
				var data = $(this).sortable('serialize');
				data += ('&page=' + PAGE);

				$.ajax({
					dataType: 'json',
					method: 'post',
					url: PATH_PANEL + 'ajax/sortable.php',
					data: data

				}).done(function(data){
					if (!data.success) {
						message('error', data.message);
					}
				})
			},
			stop: function(){
				$(this).find('.contactBox').css('border-style', 'solid');
			}
		});
	}

	if (PAGE == 'chat') {
		$('.chat').on('submit', function(){
			var action = $(this).find('input').attr('name');
			if (action == 'insert') insertChat();
			else if (action == 'reset') resetChat();
			return false;
		});

		$('.chat textarea').keyup(function(e){
			var code = e.keyCode || e.which;
			if (code == 13) insertChat();
		});

		setInterval(function(){
			getChat();
		}, 3000);
	}
}

$(function(){
	init();

	// Menu
	var open = (window.outerWidth < 769 ? false : true);
	$('.menuBtn').on('click', function(){
		if (open) {
			$('#menu').animate({width: '0'}, function(){
				$('#menu').css('display', 'none');
			});
			$('#header, #main').animate({width: '100vw', left: '0'});
			open = false;
		} else {
			if (window.outerWidth < 721) {
				$('#header, #main').animate({left: '80vw'});
				$('#menu').css('display', 'block').animate({width: '80vw'});
			} else {
				$('#header, #main').animate({width: '80vw', left: '20vw'});
				$('#menu').css('display', 'block').animate({width: '20vw'});
			}
			open = true;
		}
	});

	// Carregamento de página via ajax
	$('.menuItems a, #header [ajax]').on('click', function(){
		var link = $(this).attr('href');
		$('.loading, .loadingBox').fadeIn(200, function(){
			$('#content').load((link + ' #content'), function(){
				if (window.outerWidth < 721) {
					$('#menu').css('display', 'none').css('width', '0');
					$('#header, #main').css('width', '100vw').css('left', '0');
					open = false;
				}
				window.history.pushState('', '', link);
				init();
				$('.loading, .loadingBox').fadeOut(200);
			});
		});
		return false;
	});

	// Carregamento de formulário via ajax
	$('.ajax').ajaxForm({
		dataType: 'json',
		beforeSend: function(){
			$('.alert').remove();
			showLoading();
		},
		success: function(data){
			if (data.success) {
				$('#content').prepend("<div class='alert success'><i class='fa fa-check'></i> "+ data.message +"</div>");

					/* if (el.attr('remove') != undefined) {
						$('#content > .contentBox:nth-child(1)').remove();
					}
				// $('.ajax')[0].reset(); */
			} else {
				$('#content').prepend("<div class='alert error'><i class='fa fa-times'></i> "+ data.message +"</div>");
			}
			hideLoading();
		}
	});

	// Máscaras de formulário
	$('[name=cpf]').mask('999.999.999-99');
	$('[name=cnpj]').mask('99.999.999/9999-99');
	$('[name=installments], [name=interval]').mask('99');
	$('[name=value]').maskMoney({prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false});
	$('[name=price]').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

	$('[name=type]').change(function(){
		if ($(this).val() == 'physical') {
			$('[name=cpf]').parent().show();
			$('[name=cnpj]').parent().hide();
		} else {
			$('[name=cpf]').parent().hide();
			$('[name=cnpj]').parent().show();
		}
	});

})