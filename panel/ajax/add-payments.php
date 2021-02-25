<?php
	require_once 'config.php';
	if (!isset($_POST['submit'])) die(json_encode(2));

	$customer = isset($_POST['customer']) ? (int)$_POST['customer'] : false;
	if (!Database::rowCount("SELECT `id` FROM `site_customers` WHERE `id` = ?", array($customer))) {
		$data['success'] = false;
		$data['message'] = 'Operação inválida.';
		die(json_encode($data));
	}

	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$value = isset($_POST['value']) ? $_POST['value'] : false;
	$installments = isset($_POST['installments']) ? $_POST['installments'] : false;
	$interval = isset($_POST['interval']) ? $_POST['interval'] : false;
	$maturity = isset($_POST['maturity']) ? $_POST['maturity'] : false;

	if (!$name or !$value or !$installments or !$interval or
		!$maturity) {
		$data['success'] = false;
		$data['message'] = 'Campos vazios não são permitidos.';
		die(json_encode($data));
	}

	if (strtotime($maturity) < strtotime(date('Y-m-d'))) {
		$data['success'] = false;
		$data['message'] = 'Você selecionou uma data negativa.';
		die(json_encode($data));
	}

	for ($i = 0; $i < $installments; $i++) {
		$date = strtotime($maturity) + (($i * $interval) *
			(60 * 60 * 24));

		$sql = Database::connect()->prepare("INSERT INTO `panel_financial` VALUES (null,?,?,?,?,?)");
		if (!$sql->execute(array($customer, $name, $value, date('Y-m-d', $date), 0))) {
			$data['success'] = false;
			$data['message'] = 'Falha ao adicionar pagamento Nº '.$i;
			die(json_encode($data));
		}
	}

	$data['success'] = true;
	$data['message'] = 'Adicionado com sucesso.';
	die(json_encode($data));
?>