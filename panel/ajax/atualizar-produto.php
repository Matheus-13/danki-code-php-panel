<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$id = isset($_POST['id']) ? (int)$_POST['id'] : false;
	if (!Database::rowCount("SELECT `id` FROM `site_stock` WHERE `id` = $id")) {
		$data['success'] = false;
		$data['message'] = 'ID inválido.';
		die(json_encode($data));
	}

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$about = isset($_POST['about']) ? $_POST['about'] : false;
	$width = isset($_POST['width']) ? $_POST['width'] : false;
	$height = isset($_POST['height']) ? $_POST['height'] : false;
	$length = isset($_POST['length']) ? $_POST['length'] : false;
	$weight = isset($_POST['weight']) ? $_POST['weight'] : false;
	$price = isset($_POST['price']) ? Convert::currencyDb($_POST['price']) : false;
	$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : false;

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

		$imgs = array();

		for ($i = 0; $i < $amountImgs; $i++) {
			$currentImg = ['tmp_name' => $_FILES['img']['tmp_name'][$i], 'name' => $_FILES['img']['name'][$i]];

			$extension = explode('.', $currentImg['name']);
			$imgName = uniqid().'.'.$extension[count($extension) - 1];

			// Upload
			if (!move_uploaded_file($currentImg['tmp_name'], DIR_STOCK.$imgName)) {
				$data['success'] = false;
				$data['message'] = 'Falha no upload da imagem Nº'.($i + 1).'.';
				die(json_encode($data));

			} else {
				// Registro
				Database::connect()->exec("INSERT INTO `stock_images` VALUES (null, $id, '$imgName')");
			}
		}
	}

	if ($name) {
		if (!Database::update('site_stock', 'name', $name, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo NOME no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($about) {
		if (!Database::update('site_stock', 'about', $about, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo DESCRIÇÃO no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($width) {
		if (!Database::update('site_stock', 'width', $width, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo LARGURA no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($height) {
		if (!Database::update('site_stock', 'height', $height, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo ALTURA no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($length) {
		if (!Database::update('site_stock', 'length', $length, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo COMPRIMENTO no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($weight) {
		if (!Database::update('site_stock', 'weight', $weight, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo PESO no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($price) {
		if (!Database::update('site_stock', 'price', $price, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo PREÇO no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($quantity) {
		if (!Database::update('site_stock', 'quantity', $quantity, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo QUANTIDADE no banco de dados.';
			die(json_encode($data));
		}
	}

	$data['success'] = true;
	$data['message'] = 'Atualizado com sucesso.';
	die(json_encode($data));
?>