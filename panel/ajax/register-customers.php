<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$type = isset($_POST['type']) ? $_POST['type'] : false;
	$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : false;
	$cnpj = isset($_POST['cnpj']) ? $_POST['cnpj'] : false;

	if (!$name or !$email or !$type or (!$cpf and !$cnpj)) {
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
			if (!move_uploaded_file($img['tmp_name'], DIR_CUSTOMERS.$imgName)) {
				$data['success'] = false;
				$data['message'] = 'Falha no upload da imagem.';
				die(json_encode($data));
			}
		}
	} else {
		$imgName = null;
	}

	if ($type == 'physical') $cpf_cnpj = $cpf;
	else if ($type == 'legal') $cpf_cnpj = $cnpj;

	$sql = Database::connect()->prepare("INSERT INTO `site_customers` (`name`, `email`,	`type`,	`cpf_cnpj`,	`img`) VALUES (?,?,?,?,?)");
	if ($sql->execute(array($name, $email, $type, $cpf_cnpj, $imgName))) {
		$data['success'] = true;
		$data['message'] = 'Cliente cadastrado com sucesso.';
		die(json_encode($data));

	} else {
		@unlink(DIR_CUSTOMERS.$imgName);
		$data['success'] = false;
		$data['message'] = 'Falha ao cadastrar cliente.';
		die(json_encode($data));
	}
?>