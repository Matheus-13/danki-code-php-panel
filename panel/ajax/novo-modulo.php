<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$about = isset($_POST['about']) ? $_POST['about'] : false;
	$course = isset($_POST['course']) ? (int)$_POST['course'] : false;

	if (!$name or !$about or !$course) {
		$data['success'] = false;
		$data['message'] = 'Campos vazios não são permitidos.';
		die(json_encode($data));
	}

	if (!Database::exist('courses', $course)) {
		$data['success'] = false;
		$data['message'] = 'O curso escolhido é inválido.';
		die(json_encode($data));
	}

	// Cadastra no banco de dados
	if (!Database::insert('modules', '?,?,?,?', array(null, $course, $name, $about))) {
		$data['success'] = false;
		$data['message'] = 'O cadastro falhou.';
		die(json_encode($data));
	}

	$data['success'] = true;
	$data['message'] = 'Cadastrado com sucesso.';
	die(json_encode($data));
?>