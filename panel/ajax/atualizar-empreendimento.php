<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$id = isset($_POST['id']) ? (int)$_POST['id'] : false;
	if (!Database::exist('site_enterprises', $id)) {
		$data['success'] = false;
		$data['message'] = 'ID inválido.';
		die(json_encode($data));
	}

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$type = isset($_POST['type']) ? $_POST['type'] : false;
	$price = isset($_POST['price']) ? Convert::currencyDb($_POST['price']) : false;
	$img = isset($_FILES['img']) ? $_FILES['img'] : false;

	if ($img) {
		$validImg = Upload::validAvatar($img);
		if (!$validImg[0]) {
			$data['success'] = false;
			$data['message'] = $validImg[1];
			die(json_encode($data));

		} else {
			// Apaga imagem atual
			$imgName = Database::fetch("SELECT `img` FROM `site_enterprises` WHERE `id` = $id");
			if ($imgName) @unlink(DIR_ENTERPRISES.$imgName['img']);

			// Nome para a nova imagem
			$imgName = Upload::imgName($img);

			if (!move_uploaded_file($img['tmp_name'], DIR_ENTERPRISES.$imgName)) {
				$data['success'] = false;
				$data['message'] = 'Falha no upload da imagem.';
				die(json_encode($data));
			}

			if (!Database::update('site_enterprises', 'img', $imgName, $id)) {
				@unlink(DIR_ENTERPRISES.$imgName);
				$data['success'] = false;
				$data['message'] = 'Falha ao atualizar campo IMAGEM no banco de dados.';
				die(json_encode($data));
			}
		}
	}

	if ($name) {
		if (!Database::update('site_enterprises', 'name', $name, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo NOME no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($type) {
		if (!Database::update('site_enterprises', 'type', $type, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo TIPO no banco de dados.';
			die(json_encode($data));
		}
	}

	if ($price) {
		if (!Database::update('site_enterprises', 'price', $price, $id)) {
			$data['success'] = false;
			$data['message'] = 'Falha ao atualizar campo PREÇO no banco de dados.';
			die(json_encode($data));
		}
	}

	$data['success'] = true;
	$data['message'] = 'Atualizado com sucesso.';
	die(json_encode($data));
?>