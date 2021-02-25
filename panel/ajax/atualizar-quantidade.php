<?php
	require_once 'config.php';
	if (!isset($_POST['submit']) or !isset($_POST['quantity']) or
		!isset($_POST['id']))
		die(json_encode(2));

	$quantity = (int)$_POST['quantity'];
	if ($quantity < 0) {
		$data['success'] = false;
		$data['message'] = 'O valor não pode ser negativo.';
		die(json_encode($data));
	}

	$id = (int)$_POST['id'];
	if (!Database::rowCount("SELECT `id` FROM `site_stock` WHERE `id` = $id")) {
		$data['success'] = false;
		$data['message'] = 'Produto inválido.';
		die(json_encode($data));
	}

	if (!Database::update('site_stock', 'quantity', $quantity, $id)) {
		$data['success'] = false;
		$data['message'] = 'Falha ao atualizar no banco de dados.';
		die(json_encode($data));
	}

	$data['success'] = true;
	$data['message'] = 'Sucesso!';
	die(json_encode($data));
?>