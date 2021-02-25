<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$link = isset($_POST['link']) ? $_POST['link'] : false;
	$module = isset($_POST['module']) ? (int)$_POST['module'] : false;

	if (!$name or !$link or !$module) {
		$data['success'] = false;
		$data['message'] = 'Campos vazios não são permitidos.';
		die(json_encode($data));
	}

	if (!Database::exist('modules', $module)) {
		$data['success'] = false;
		$data['message'] = 'O módulo escolhido é inválido.';
		die(json_encode($data));
	}

	// Cadastra no banco de dados
	if (!Database::insert('classes', '?,?,?,?', array(null, $module, $name, $link))) {
		$data['success'] = false;
		$data['message'] = 'O cadastro falhou.';
		die(json_encode($data));
	}

	$data['success'] = true;
	$data['message'] = 'Cadastrado com sucesso.';
	die(json_encode($data));
?>