<?php
	require_once 'config.php';

	$url = isset($_GET['url']) ? explode("/", $_GET['url']) : (array)'';

	if (!Login::logged()) {
		require_once('page/login.php');
		die();

	} else if ($url[0] == 'logout') {
		Login::logout();

	} else {
		require_once('page/main.php');
	}
?>