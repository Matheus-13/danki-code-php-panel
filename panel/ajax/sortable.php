<?php
	require_once 'config.php';

	$ids = isset($_POST['item']) ? $_POST['item'] : false;

	if (!$ids) {
		$data['success'] = false;
		$data['message'] = 'Operação inválida.';
		die(json_encode($data));
	}

	$page = isset($_POST['page']) ? $_POST['page'] : false;

	switch ($page) {
		case 'enterprises':
			$i = 1;
			foreach ($ids as $value) {
				Database::connect()->exec("UPDATE `site_enterprises` SET `order` = $i WHERE `id` = $value");
				$i++;
			}
		break;

		default:
			$data['success'] = false;
			$data['message'] = 'Operação inválida.';
			die(json_encode($data));
		break;
	}

	$data['success'] = true;
	die(json_encode($data));
?>