<?php
	class Site
	{
		public static function redirect($path) {
			echo "<script>
			location.href='$path'
			</script>";
			die();
		}

		public static function printHead($title) {
			global $infoSite;
			echo "<meta charset='utf-8' />
			<title>$title</title>
			<link rel='icon' href='".PATH."favicon.ico' type='image/x-icon' />
			<link rel='stylesheet' href='".PATH."css/font-awesome.min.css' />
			<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' />
			<link href='".PATH."css/style.css' rel='stylesheet' />
			<meta name='viewport' content='width=device-width, initial-scale=1.0' />
			<meta name='author' content='Matheus Ramalho de Oliveira' />
			<meta name='description' content='".$infoSite['description']."' />
			<meta name='keywords' content='".$infoSite['keywords']."' />";
		}

		public static function printHeader() {
			global $url;
			echo "<div class='center'>
			<div class='logo left'><a href='".PATH."'>Logomarca</a></div><!--logo-->
			<nav class='desktop right'>
				<ul>
					<li><a title='Home' href='".PATH."'>Home</a></li>
					<li><a title='Depoimentos' href='".PATH."depoimentos'>Depoimentos</a></li>
					<li><a title='Serviços' href='".PATH."servicos'>Serviços</a></li>
					<li><a href='".PATH."noticias'>Notícias</a></li>
					<li><a realtime='contato' href='".PATH."contato'>Contato</a></li>
				</ul>
			</nav>
			 <nav class='mobile right'>
			 	<div class='botao-menu-mobile'>
			 		<i class='fa fa-bars' aria-hidden='true'></i>
			 	</div>
				<ul>
					<li><a href='".PATH."'>Home</a></li>
					<li><a href='".PATH."depoimentos'>Depoimentos</a></li>
					<li><a href='".PATH."servicos'>Serviços</a></li>
					<li><a href='".PATH."noticias'>Notícias</a></li>
					<li><a realtime='contato' href='".PATH."contato'>Contato</a></li>
				</ul>
			</nav>
			<div class='clear'></div><!--clear-->
			</div><!--center-->
			<base base='".PATH."' />
			<target target='$url[0]' />";
		}

		public static function printScript() {
			echo "<script src='".PATH."js/jquery.js'></script>
			<script src='".PATH."js/constants.js'></script>
			<script src='".PATH."js/scripts.js'></script>
			<script src='".PATH."js/slider.js'></script>
			<script src='".PATH."js/exemplo.js'></script>
			<script src='".PATH."js/formularios.js'></script>
			<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDHPNQxozOzQSZ-djvWGOBUsHkBUoT_qH4'></script>
			<script src='".PATH."js/map.js'></script>";
		}

		public static function printFooter() {
			echo "<div class='center'>
			<p>Todos os direitos reservados</p>
			</div><!--center-->";
		}
	}
?>