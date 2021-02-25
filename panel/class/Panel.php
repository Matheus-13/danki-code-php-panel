<?php
	class Panel
	{
		public static function generateSlug($str){
			$str = mb_strtolower($str);
			$str = preg_replace('/(â|á|ã)/', 'a', $str);
			$str = preg_replace('/(ê|é)/', 'e', $str);
			$str = preg_replace('/(í|Í)/', 'i', $str);
			$str = preg_replace('/(ú)/', 'u', $str);
			$str = preg_replace('/(ó|ô|õ|Ô)/', 'o',$str);
			$str = preg_replace('/(_|\/|!|\?|#)/', '',$str);
			$str = preg_replace('/( )/', '-',$str);
			$str = preg_replace('/ç/','c',$str);
			$str = preg_replace('/(-[-]{1,})/','-',$str);
			$str = preg_replace('/(,)/','-',$str);
			$str=strtolower($str);
			return $str;
		}

		public static function pagination($table, $step) {
			$pages['step'] = $step;
			$pages['total'] = ceil(Database::rowCount("SELECT `id` FROM `$table`") / $pages['step']);
			$pages['page'] = isset($_GET['page']) ? (int)$_GET['page'] : 1;

			if ($pages['page'] < 1) $pages['page'] = 1;
			else if ($pages['page'] > $pages['total']) $pages['page'] = $pages['total'];

			return $pages;
		}

		public static function printPagination($current, $total) {
			for ($i = 1; $i <= $total; $i++) {
				if ($i == $current)
					echo "<a class='active' href='?page=".$i."'>".$i."</a>";
				else
					echo "<a href='?page=".$i."'>".$i."</a>";
			}
		}

		public static function recoverPost($post, $content = false) {
			if (isset($_POST[$post])) {
				echo $_POST[$post];
			} else if ($content) {
				echo $content;
			}
		}

		public static function printHead() {
			echo "<meta charset='utf-8' />
			<title>Painel de Controle</title>
			<link rel='icon' href='".PATH."favicon.ico' type='image/x-icon' />
			<meta name='viewport' content='width=device-width, initial-scale=1.0' />
			<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' />
			<link rel='stylesheet' href='".PATH_PANEL."css/all.min.css' />
			<link href='".PATH_PANEL."css/jquery-ui.min.css' rel='stylesheet' />
			<link href='".PATH_PANEL."css/style.css' rel='stylesheet' />
			<link href='".PATH_PANEL."css/color.css' rel='stylesheet' />
			<meta name='author' content='Matheus Ramalho de Oliveira' />
			<constants path='".PATH."' pathpanel='".PATH_PANEL."' />";
		}

		public static function printHeader() {
		}

		public static function printScript() {
			echo "<script src='".PATH."js/jquery.js'></script>
			<script src='".PATH_PANEL."js/jquery-ui.min.js'></script>
			<script src='".PATH_PANEL."js/jquery.ajaxform.js'></script>
			<script src='".PATH_PANEL."js/jquery.mask.js'></script>
			<script src='".PATH_PANEL."js/jquery.maskMoney.js'></script>
			<script src='".PATH_PANEL."js/tinymce.min.js'></script>
			<script src='".PATH_PANEL."js/script.js'></script>";
		}

		public static function printFooter() {
		}

		public static function loadPage() {
			global $url;
			if (file_exists('page/'.$url[0].'.php'))
				require_once('page/'.$url[0].'.php');
			else
				require_once('page/home.php');
		}

		public static function alert($type, $message) {
			if ($type == 'success') {
				echo "<div class='alert success'><i class='fa fa-check'></i> ".$message."</div>";
			} else if ($type == 'error') {
				echo "<div class='alert error'><i class='fa fa-times'></i> ".$message."</div>";
			} else if ($type == 'warning') {
				echo "<div class='alert warning'><i class='fa fa-warning'></i> ".$message."</div>";
			}
		}
	}
?>