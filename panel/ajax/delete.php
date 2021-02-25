<?php
	require_once 'config.php';

	$id = isset($_POST['id']) ? (int)$_POST['id'] : false;
	$type = isset($_POST['type']) ? $_POST['type'] : false;

	switch ($type) {
		case 'customer':
			$table = 'site_customers';
		break;

		case 'product':
			$table = 'site_stock';
		break;

		case 'productImg':
			$table = 'stock_images';
		break;

		case 'enterprise':
			$table = 'site_enterprises';
		break;

		case 'property':
			$table = 'enterprise_properties';
		break;

		case 'propertyImg':
			$table = 'property_images';
		break;

		case 'task':
			$table = 'panel_schedule';
		break;
		
		default:
			$data['success'] = false;
			$data['message'] = 'Operação inválida.';
			die(json_encode($data));
		break;
	}

	if (!Database::rowCount("SELECT `id` FROM `$table` WHERE `id` = ?", array($id))) {
		$data['success'] = false;
		$data['message'] = 'Item inválido.';
		die(json_encode($data));
	}

	// Passou pela validação, apague
	switch ($type) {
		case 'customer':
			$img = Database::fetch("SELECT `img` FROM `$table` WHERE `id` = ?", array($id));
			if ($img)
				@unlink(DIR_CUSTOMERS.$img['img']);

			if (!Database::delete('panel_financial', 'customer',
				$id)) {
				$data['success'] = false;
				$data['message'] = 'Falha ao excluir pagamentos.';
				die(json_encode($data));
			}

			if (!Database::delete($table, 'id', $id)) {
				$data['success'] = false;
				$data['message'] = 'Falha ao excluir cliente.';
				die(json_encode($data));
			}
		break;

		case 'product':
			// Apaga imagens
			$imgs = Database::fetchAll("SELECT `id`, `img` FROM `stock_images` WHERE `product` = $id");
			foreach ($imgs as $key => $value) {
				@unlink(DIR_STOCK.$value['img']);
			}

			// Apaga do BD
			Database::delete('stock_images', 'product', $id);
			Database::delete($table, 'id', $id);
		break;

		case 'productImg':
			$img = Database::fetch("SELECT `img` FROM `$table` WHERE `id` = $id");
			@unlink(DIR_STOCK.$img['img']);
			Database::delete($table, 'id', $id);
		break;

		case 'enterprise':
			// Seleciona imóveis do empreendimento
			$properties = Database::selectAll('id', 'enterprise_properties', 'enterpriseID = '.$id);
			foreach ($properties as $value) {
				// Seleciona e apaga imagens do imóvel
				$propertyImgs = Database::selectAll('img', 'property_images', 'propertyID = '.$value['id']);
				foreach ($propertyImgs as $value2) {
					@unlink(DIR_ENTERPRISES.$value2['img']);
				}
				// Apaga imagens do BD
				Database::delete('property_images', 'propertyID', $value['id']);
			}

			// Apaga imóveis do BD
			Database::delete('enterprise_properties', 'enterpriseID', $id);

			$img = Database::select('img', $table, 'id = '.$id);
			@unlink(DIR_ENTERPRISES.$img['img']);
			Database::delete($table, 'id', $id);
		break;

		case 'property':
			// Apaga imagens
			$imgs = Database::selectAll('img', 'property_images', 'propertyID = '.$id);
			foreach ($imgs as $value) {
				@unlink(DIR_ENTERPRISES.$value['img']);
			}

			// Apaga do BD
			Database::delete('property_images', 'propertyID', $id);
			Database::delete($table, 'id', $id);
		break;

		case 'propertyImg':
			$img = Database::select('img', $table, 'id = '.$id);
			@unlink(DIR_ENTERPRISES.$img['img']);
			Database::delete($table, 'id', $id);
		break;

		case 'task':
			Database::delete($table, 'id', $id);
		break;
	}

	$data['success'] = true;
	die(json_encode($data));
?>