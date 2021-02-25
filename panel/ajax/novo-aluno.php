<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$surname = isset($_POST['surname']) ? $_POST['surname'] : false;
	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$password = isset($_POST['password']) ? $_POST['password'] : false;

	if (!$name or !$surname or !$email or !$password) {
		$data['success'] = false;
		$data['message'] = 'Apenas o campo IMAGEM pode ficar vazio.';
		die(json_encode($data));
	}

	$img = isset($_FILES['img']) ? $_FILES['img'] : false;

	if ($img) {
		$validImg = Upload::validAvatar($img);
		if (!$validImg[0]) {
			$data['success'] = false;
			$data['message'] = $validImg[1];
			die(json_encode($data));
		}
	}

	$idExistent = true;
	while ($idExistent) {
		$id = uniqid();
		$sql = Database::connect()->prepare("SELECT `id` FROM `students` WHERE `id` = ?");
		$sql->execute(array($id));
		if (!$sql->rowCount()) {
			$idExistent = false;
		}
	}

	if ($img) {
		$extension = explode('.', $img['name']);
		$imgName = $id.'.'.$extension[count($extension) - 1];

		if (!move_uploaded_file($img['tmp_name'], DIR_STUDENTS.$imgName)) {
			$data['success'] = false;
			$data['message'] = 'Falha no upload da imagem.';
			die(json_encode($data));
		}

	} else {
		$imgName = null;
	}

	// Cadastra no banco de dados
	if (!Database::insert('students', '?,?,?,?,?,?', array($id, $name, $surname, $email, $password, $imgName))) {
		@unlink(DIR_STUDENTS.$imgName);
		$data['success'] = false;
		$data['message'] = 'O cadastro falhou.';
		die(json_encode($data));
	}

	$data['success'] = true;
	$data['message'] = 'Cadastrado com sucesso.';
	die(json_encode($data));
?>