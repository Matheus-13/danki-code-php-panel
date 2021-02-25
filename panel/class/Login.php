<?php
	class Login
	{
		public static function logged() {
			if (isset($_SESSION['id'])) {
				return true;

			} else if (isset($_COOKIE['id']) and isset($_COOKIE['token'])) {
				$sql = Database::connect()->prepare("SELECT `id`, `date` FROM `panel_remember` WHERE `id` = ? AND `token` = ?");
				$sql->execute(array($_COOKIE['id'], $_COOKIE['token']));
				if (!$sql->rowCount()) return false;
				$remember = $sql->fetch();
				if (date('Y-m-d') > $remember['date']) return false;

				$sql = Database::connect()->prepare("SELECT id, name FROM `panel_admins` WHERE id = ?");
				$sql->execute(array($remember['id']));
				$sql = $sql->fetch();
				$_SESSION['id'] = $sql['id'];
				$_SESSION['name'] = $sql['name'];

				header('Location: '.PATH_PANEL);
				die();

			} else {
				return false;
			}
		}

		public static function enter($email, $password, $remember) {
			$sql = Database::connect()->prepare("SELECT id, name FROM `panel_admins` WHERE email = ? AND password = ?");
			$sql->execute(array($email, $password));
			if ($sql->rowCount() == 1) {
				$sql = $sql->fetch();
				$_SESSION['id'] = $sql['id'];
				$_SESSION['name'] = $sql['name'];

				if ($remember) {
					$token = self::updateToken();
					if (!$token[0]) return $token;
					setcookie('id', $_SESSION['id'], time()+(60*60*24*7), '/');
					setcookie('token', $token[1], time()+(60*60*24*7), '/');
				}

				header('Location: '.PATH_PANEL);
				die();
			} else {
				return array(false, 'Email ou senha incorreto.');
			}
		}

		public static function logout() {
			setcookie('id', false, time()-(1), '/');
			setcookie('token', false, time()-(1), '/');
			session_destroy();
			header('Location: '.PATH_PANEL);
			die();
		}

		public static function tokenDate() {
			return date('Y-m-d', time()+(60*60*24*7));
		}

		public static function createToken() {
			$tokenExistent = true;
			while ($tokenExistent) {
				$token = md5(uniqid(rand(), true));
				$sql = Database::connect()->prepare("SELECT id FROM `panel_remember` WHERE token = ?");
				$sql->execute(array($token));
				if ($sql->rowCount() == 0) $tokenExistent = false;
			}
			return $token;
		}

		public static function updateToken() {
			$token = self::createToken();

			$sql = Database::connect()->prepare("SELECT id FROM `panel_remember` WHERE id = ?");
			$sql->execute(array($_SESSION['id']));
			if ($sql->rowCount() == 0) {
				$sql = Database::connect()->prepare("INSERT INTO `panel_remember` (`id`, `token`, `date`) VALUES (?,?,?)");
				if (!$sql->execute(array($_SESSION['id'], $token, self::tokenDate())))
					return array(false, 'Falha ao registrar no banco de dados.');
			} else {
				$sql = Database::connect()->prepare("UPDATE `panel_remember` SET `token` = ?, `date` = ? WHERE `id` = ?");
				if (!$sql->execute(array($token, self::tokenDate(), $_SESSION['id'])))
					return array(false, 'Falha ao atualizar no banco de dados.');
			}
			return array(true, $token);
		}
	}
?>