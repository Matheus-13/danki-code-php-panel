<?php
	require_once 'config.php';

	$id = isset($_POST['id']) ? (int)$_POST['id'] : false;

	if (!Database::rowCount("SELECT `id` FROM `panel_financial` WHERE `id` = ?", array($id))) {
		$data['success'] = false;
		$data['message'] = 'Item inválido.';
		die(json_encode($data));
	}

	if (Database::update('panel_financial', 'status', 1, $id)) {
		$data['success'] = true;
		die(json_encode($data));

	} else {
		$data['success'] = false;
		$data['message'] = 'Falha no banco de dados.';
		die(json_encode($data));
	}
?>