<?php
	require_once 'config.php';

	Database::updateOnlineUser();
	Database::visit();

	$query = "SELECT * FROM `site_head`";
	$infoSite = Database::fetch($query);

	$url = isset($_GET['url']) ? explode("/", $_GET['url']) : (array)'home';

	if (($url[0] == 'home') or ($url[0] == 'index') or
		($url[0] == 'depoimentos') or ($url[0] == 'servicos') or
		($url[0] == 'contato')) {
		require_once("page/home.php");
	} else if ($url[0] == 'noticias') {
		require_once("page/noticias.php");
	} else {
		require_once("page/404.php");
	}
?>