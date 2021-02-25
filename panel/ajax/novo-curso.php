<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$about = isset($_POST['about']) ? $_POST['about'] : false;

	if (!$name or !$about) {
		$data['success'] = false;
		$data['message'] = 'Campos vazios não são permitidos.';
		die(json_encode($data));
	}

	// Cadastra no banco de dados
	if (!Database::insert('courses', '?,?,?', array(null, $name, $about))) {
		$data['success'] = false;
		$data['message'] = 'O cadastro falhou.';
		die(json_encode($data));
	}

	$data['success'] = true;
	$data['message'] = 'Cadastrado com sucesso.';
	die(json_encode($data));
?>