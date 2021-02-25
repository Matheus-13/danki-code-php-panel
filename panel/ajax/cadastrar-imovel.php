<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$enterpriseID = isset($_POST['enterpriseID']) ? (int)$_POST['enterpriseID'] : false;

	if (!Database::exist('site_enterprises', $enterpriseID)) {
		$data['success'] = false;
		$data['message'] = 'ID inválido.';
		die(json_encode($data));
	}

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$price = isset($_POST['price']) ? Convert::currencyDb($_POST['price']) : false;
	$area = isset($_POST['area']) ? $_POST['area'] : false;

	if (!$name or !$price or !$area) {
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
	$sql = Database::connect()->prepare("INSERT INTO `enterprise_properties` VALUES (null,?,?,?,?)");
	$sql->execute(array($enterpriseID, $name, $price, $area));
	$lastId = Database::connect()->lastInsertId();

	if ($amountImgs) {
		for ($i = 0; $i < $amountImgs; $i++) {
			$currentImg = ['tmp_name' => $_FILES['img']['tmp_name'][$i], 'name' => $_FILES['img']['name'][$i]];

			$imgName = Upload::imgName($currentImg);

			if (!move_uploaded_file($currentImg['tmp_name'], DIR_ENTERPRISES.$imgName)) {
				$data['success'] = false;
				$data['message'] = 'O cadastro do imóvel foi realizado, mas o upload da imagem Nº'.($i + 1).' falhou.';
				die(json_encode($data));

			} else {
				Database::connect()->exec("INSERT INTO `property_images` VALUES (null, $lastId, '$imgName')");
			}
		}
	}

	$data['success'] = true;
	$data['message'] = 'Imóvel cadastrado com sucesso.';
	die(json_encode($data));
?>