<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$id = isset($_POST['id']) ? (int)$_POST['id'] : false;
	if (!Database::rowCount("SELECT `id` FROM `site_customers` WHERE `id` = ?", array($id))) {
		$data['success'] = false;
		$data['message'] = 'Item inválido.';
		die(json_encode($data));
	}

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$type = isset($_POST['type']) ? $_POST['type'] : false;
	$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : false;
	$cnpj = isset($_POST['cnpj']) ? $_POST['cnpj'] : false;
	$img = isset($_FILES['img']) ? $_FILES['img'] : false;

	if ($img) {
		// Valida imagem
		$validImg = Upload::validAvatar($img);
		if (!$validImg[0]) {
			$data['success'] = false;
			$data['message'] = $validImg[1];
			die(json_encode($data));
		}

		// Apaga imagem existente
		$imgName = Database::fetch("SELECT `img` FROM `site_customers` WHERE `id` = $id");
		if ($imgName['img'])
			@unlink(DIR_CUSTOMERS.$imgName['img']);

		// Nome para a imagem nova
		$extension = explode('.', $img['name']);
		$imgName = uniqid().'.'.$extension[count($extension) - 1];

		if (!move_uploaded_file($img['tmp_name'], DIR_CUSTOMERS.$imgName)) {
			$data['success'] = false;
			$data['message'] = 'Falha no upload da imagem.';
			die(json_encode($data));
		}

		$sql = Database::connect()->prepare("UPDATE `site_customers` SET `img` = ? WHERE `id` = ?");
		if (!$sql->execute(array($imgName, $id))) {
			@unlink(DIR_CUSTOMERS.$imgName);
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo IMAGEM no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($type) {
		if ($type == 'physical') $cpf_cnpj = $cpf;
		else if ($type == 'legal') $cpf_cnpj = $cnpj;

		$sql = Database::connect()->prepare("UPDATE `site_customers` SET `type` = ?, `cpf_cnpj` = ? WHERE `id` = ?");
		if (!$sql->execute(array($type, $cpf_cnpj, $id))) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo CPF/CNPJ no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($email) {
		$sql = Database::connect()->prepare("UPDATE `site_customers` SET `email` = ? WHERE `id` = ?");
		if (!$sql->execute(array($email, $id))) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo E-MAIL no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($name) {
		$sql = Database::connect()->prepare("UPDATE `site_customers` SET `name` = ? WHERE `id` = ?");
		if (!$sql->execute(array($name, $id))) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo NOME no banco de dados.';
			die(json_encode($data));
		}
	}

	$data['success'] = true;
	$data['message'] = 'Cliente atualizado com sucesso.';
	die(json_encode($data));
?>