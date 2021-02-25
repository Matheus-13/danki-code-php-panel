<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$id = isset($_POST['id']) ? (int)$_POST['id'] : false;
	if (!Database::exist('enterprise_properties', $id)) {
		$data['success'] = false;
		$data['message'] = 'ID inválido.';
		die(json_encode($data));
	}

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$price = isset($_POST['price']) ? Convert::currencyDb($_POST['price']) : false;
	$area = isset($_POST['area']) ? $_POST['area'] : false;

	$amountImgs = isset($_FILES['img']['name']) ? count($_FILES['img']['name']) : 0;

	if ($amountImgs) {
		// Valida imagens
		for ($i = 0; $i < $amountImgs; $i++) {
			$currentImg = ['type' => $_FILES['img']['type'][$i],
			'size' => $_FILES['img']['size'][$i]];
			$validImg = Upload::validAvatar($currentImg);

			if (!$validImg[0]) {
				$data['success'] = false;
				$data['message'] = $validImg[1];
				die(json_encode($data));
			}
		}

		// Upload das imagens
		for ($i = 0; $i < $amountImgs; $i++) {
			$currentImg = ['name' => $_FILES['img']['name'][$i], 'tmp_name' => $_FILES['img']['tmp_name'][$i]];
			$imgName = Upload::imgName($currentImg);

			if (!move_uploaded_file($currentImg['tmp_name'], DIR_ENTERPRISES.$imgName)) {
				$data['success'] = false;
				$data['message'] = 'Falha no upload da imagem Nº'.($i + 1).'.';
				die(json_encode($data));

			} else {
				Database::connect()->exec("INSERT INTO `property_images` VALUES (null, $id, '$imgName')");
			}
		}
	}

	if ($name) {
		if (!Database::update('enterprise_properties', 'name', $name, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo NOME no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($price) {
		if (!Database::update('enterprise_properties', 'price', $price, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo PREÇO no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($area) {
		if (!Database::update('enterprise_properties', 'area', $area, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo ÁREA no banco de dados.';
			die(json_encode($data));
		}
	}

	$data['success'] = true;
	$data['message'] = 'Atualizado com sucesso.';
	die(json_encode($data));
?>