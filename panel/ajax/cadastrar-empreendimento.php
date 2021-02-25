<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$type = isset($_POST['type']) ? $_POST['type'] : false;
	$price = isset($_POST['price']) ? Convert::currencyDb($_POST['price']) : false;

	if (!$name or !$type or !$price) {
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

		} else {
			$extension = explode('.', $img['name']);
			$imgName = uniqid().'.'.$extension[count($extension) - 1];

			if (!move_uploaded_file($img['tmp_name'], DIR_ENTERPRISES.$imgName)) {
				$data['success'] = false;
				$data['message'] = 'Falha no upload da imagem.';
				die(json_encode($data));
			}
		}

	} else {
		$imgName = null;
	}

	$order = Database::position('site_enterprises');

	// Cadastra no banco de dados
	$sql = Database::connect()->prepare("INSERT INTO `site_enterprises` VALUES (null,?,?,?,?,?)");
	if (!$sql->execute(array($name, $type, $price, $imgName, $order))) {
		@unlink(DIR_ENTERPRISES.$imgName);
		$data['success'] = false;
		$data['message'] = 'O cadastro falhou.';
		die(json_encode($data));
	}

	$data['success'] = true;
	$data['message'] = 'Cadastrado com sucesso.';
	die(json_encode($data));
?>