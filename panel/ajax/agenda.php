<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$task = isset($_POST['task']) ? $_POST['task'] : false;
	$date = isset($_POST['date']) ? $_POST['date'] : false;

	if (!$task or !$date) {
		$data['success'] = false;
		$data['message'] = 'Campos vazios não são permitidos.';
		die(json_encode($data));
	}

	if (!Database::insert('panel_schedule', '?,?,?', array(null, $task, $date))) {
		$data['success'] = false;
		$data['message'] = 'Falha ao cadastrar no banco de dados.';
		die(json_encode($data));
	}

	$data['success'] = true;
	$data['message'] = 'Tarefa adicionada.';
	die(json_encode($data));
?>