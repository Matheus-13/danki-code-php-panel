<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$about = isset($_POST['about']) ? $_POST['about'] : false;
	$width = isset($_POST['width']) ? $_POST['width'] : false;
	$height = isset($_POST['height']) ? $_POST['height'] : false;
	$length = isset($_POST['length']) ? $_POST['length'] : false;
	$weight = isset($_POST['weight']) ? $_POST['weight'] : false;
	$price = isset($_POST['price']) ? Convert::currencyDb($_POST['price']) : false;
	$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : false;

	if (!$name or !$about or !$width or !$height or !$length or
		!$weight or !$price or !$quantity) {
		$data['success'] = false;
		$data['message'] = 'Apenas o campo IMAGEM pode ficar vazio.';
		die(json_encode($data));
	}

	$amountImgs = isset($_FILES['img']['name']) ? count($_FILES['img']['name']) : 0;

	// Valida imagens
	if ($amountImgs) {
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

	}

	// Cadastra no banco de dados
	$sql = Database::connect()->prepare("INSERT INTO `site_stock` VALUES (null,?,?,?,?,?,?,?,?)");
	$sql->execute(array($name, $about, $width, $height, $length, $weight, $price, $quantity));
	$lastId = Database::connect()->lastInsertId();

	if ($amountImgs) {
		$imgs = array();

		for ($i = 0; $i < $amountImgs; $i++) {
			$currentImg = ['tmp_name' => $_FILES['img']['tmp_name'][$i], 'name' => $_FILES['img']['name'][$i]];

			$extension = explode('.', $currentImg['name']);
			$imgName = uniqid().'.'.$extension[count($extension) - 1];

			if (!move_uploaded_file($currentImg['tmp_name'], DIR_STOCK.$imgName)) {
				$data['success'] = false;
				$data['message'] = 'O cadastro do produto foi realizado, mas o upload de uma(s) imagem falhou.';
				die(json_encode($data));

			} else {
				Database::connect()->exec("INSERT INTO `stock_images` VALUES (null, $lastId, '$imgName')");
			}
		}
	}

	$data['success'] = true;
	$data['message'] = 'Produto cadastrado com sucesso.';
	die(json_encode($data));
?>