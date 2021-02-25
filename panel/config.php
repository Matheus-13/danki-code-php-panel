<?php
	/*
		Configurações do Banco de Dados: classe Database.
	*/

	session_start();
	date_default_timezone_set('America/Sao_Paulo');

	// PHPMailer
	require_once 'class/PHPMailer/src/Exception.php';
	require_once 'class/PHPMailer/src/PHPMailer.php';
	require_once 'class/PHPMailer/src/SMTP.php';
	// ---------
	spl_autoload_register(function($class) {
		require_once 'class/'.$class.'.php';
	});

	define('COMPANY_NAME', 'Nome da Empresa');
	define('PATH', 'http://localhost/Projeto_01_Matheus/');
	define('PATH_PANEL', 'http://localhost/Projeto_01_Matheus/panel/');
	define('DIR', realpath(__DIR__.'/..').'/');
	define('DIR_PANEL', __DIR__.'/');

	define('PATH_AVATAR', PATH.'img/staff/');
	define('PATH_CUSTOMERS', PATH.'img/customers/');
	define('PATH_DEPOSITIONS', PATH.'img/depositions/');
	define('PATH_ENTERPRISES', PATH.'img/enterprises/');
	define('PATH_SERVICES', PATH.'img/services/');
	define('PATH_SITE', PATH.'img/site/');
	define('PATH_SLIDE', PATH.'img/slide/');
	define('PATH_STOCK', PATH.'img/stock/');
	define('PATH_STUDENTS', PATH.'img/students/');

	define('DIR_AVATAR', DIR.'img/staff/');
	define('DIR_CUSTOMERS', DIR.'img/customers/');
	define('DIR_DEPOSITIONS', DIR.'img/depositions/');
	define('DIR_ENTERPRISES', DIR.'img/enterprises/');
	define('DIR_SERVICES', DIR.'img/services/');
	define('DIR_SITE', DIR.'img/site/');
	define('DIR_SLIDE', DIR.'img/slide/');
	define('DIR_STOCK', DIR.'img/stock/');
	define('DIR_STUDENTS', DIR.'img/students/');
?>