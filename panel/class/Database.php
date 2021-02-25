<?php
	class Database
	{
		private static $pdo;
		private static $dbNAME = 'project';
		private static $HOST = 'localhost';
		private static $USER = 'root';
		private static $PASSWORD = '';

		public static function connect() {
			if(self::$pdo == null) {
				try {
					self::$pdo = new PDO('mysql:host='.self::$HOST.';dbname='.self::$dbNAME,self::$USER,self::$PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				} catch(Exception $e) {
					echo 'Error connecting to database';
				}
			}
			return self::$pdo;
		}

		public static function fetch($query, $execute = false) {
			$sql = self::connect()->prepare($query);
			if (!$execute) $sql->execute();
			else $sql->execute($execute);
			return $sql->fetch();
		}

		public static function fetchOne($select, $table, $where, $value, $column) {
			$sql = self::connect()->prepare("SELECT `$select` FROM `$table` WHERE $where = ?");
			$sql->execute(array($value));
			return $sql->fetch()[$column];
		}

		public static function fetchAll($query) {
			$sql = self::connect()->prepare($query);
			$sql->execute();
			return $sql->fetchAll();
		}

		public static function fetchArray($query) {
			$sql = self::connect()->prepare($query);
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
		}

		public static function fetchData($query, $start = false, $end = false, $execute = false) {
			if (!$end)
				$sql = self::connect()->prepare($query);
			else
				$sql = self::connect()->prepare($query." LIMIT $start, $end");

			try {
				if (is_array($execute))
					$sql->execute($execute);
				else
					$sql->execute();
			} catch (Exception $e) {
				// echo $e;
			}
			
			return $sql->fetchAll();
		}

		public static function select($column, $table, $where) {
			$sql = self::connect()->prepare("SELECT $column FROM `$table` WHERE $where");
			$sql->execute();
			return $sql->fetch();
		}

		public static function selectAll($column, $table, $where = false) {
			if ($where)
				$sql = self::connect()->prepare("SELECT $column FROM `$table` WHERE $where");
			else
				$sql = self::connect()->prepare("SELECT $column FROM `$table`");

			$sql->execute();
			return $sql->fetchAll();
		}

		public static function selectArray($select, $table) {
			$sql = self::connect()->prepare("SELECT $select FROM `$table`");
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
		}

		public static function rowCount($query, $execute = false) {
			$sql = self::connect()->prepare($query);
			if (!$execute) $sql->execute();
			else $sql->execute($execute);
			return $sql->rowCount();
		}

		public static function exist($table, $id) {
			$sql = self::connect()->prepare("SELECT `id` FROM `$table` WHERE `id` = $id LIMIT 1");
			$sql->execute();
			return $sql->rowCount();
		}

		public static function insert($table, $values, $execute) {
			$sql = self::connect()->prepare("INSERT INTO `$table` VALUES ($values)");
			return $sql->execute($execute);
		}

		public static function update($table, $item, $value, $id) {
			$sql = self::connect()->prepare("UPDATE `$table` SET `$item` = ? WHERE `id` = ?");
			return $sql->execute(array($value, $id));
		}

		public static function delete($table, $item, $value) {
			$sql = self::connect()->prepare("DELETE FROM `$table` WHERE `$item` = ?");
			return $sql->execute(array($value));
		}

		public static function position($table) {
			$position = self::fetch("SELECT MAX(`order`) FROM `$table`");
			$position[0]++;
			return $position[0];
		}

		public static function order($type, $table, $id) {
			if ($type == 'up') {
				$item = self::fetch("SELECT `id`, `order` FROM `$table` WHERE `id` = ?", array($id));
				if ($item['order'] == 1) return array(true);
				$beforeItem = self::fetch("SELECT `id`, `order` FROM `$table` WHERE `order` < ? ORDER BY `order` DESC LIMIT 1", array($item['order']));

				if (!self::update($table, 'order', $beforeItem['order'], $item['id']))
					return array(false, 'Falha ao atualizar campo ORDEM no banco de dados.');
				if (!self::update($table, 'order', $item['order'], $beforeItem['id']))
					return array(false, 'Falha ao atualizar campo ORDEM no banco de dados.');

			} else if ($type == 'down') {
				$item = self::fetch("SELECT `id`, `order` FROM `$table` WHERE `id` = ?", array($id));

				// Verifica se existe próxima linha
				if (!self::rowCount("SELECT `id` FROM `$table` WHERE `order` > ? ORDER BY `order` ASC LIMIT 1", array($item['order'])))
					return array(true);

				$afterItem = self::fetch("SELECT `id`, `order` FROM `$table` WHERE `order` > ? ORDER BY `order` ASC LIMIT 1", array($item['order']));

				if (!self::update($table, 'order', $afterItem['order'], $item['id']))
					return array(false, 'Falha ao atualizar campo ORDEM no banco de dados.');
				if (!self::update($table, 'order', $item['order'], $afterItem['id']))
					return array(false, 'Falha ao atualizar campo ORDEM no banco de dados.');
			}
			return array(true);
		}

		public static function updateOnlineUser() {
			if (isset($_SESSION['online'])) {
				$check = self::connect()->prepare("SELECT `id` FROM `site_online` WHERE `id` = ?");
				$check->execute(array($_SESSION['online']));
				if ($check->rowCount() == 1) {
					$sql = self::connect()->prepare("UPDATE `site_online` SET `last_action` = ? WHERE `id` = ?");
					$sql->execute(array(date('Y-m-d H:i:s'), $_SESSION['online']));
				} else {
					$sql = self::connect()->prepare("INSERT INTO `site_online` VALUES (?,?,?)");
					$sql->execute(array($_SESSION['online'], $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s')));
				}
			} else {
				$_SESSION['online'] = uniqid();
				$sql = self::connect()->prepare("INSERT INTO `site_online` VALUES (?,?,?)");
				$sql->execute(array($_SESSION['online'], $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s')));
			}
		}

		public static function cleanOnlineUsers() {
			$date = date('Y-m-d H:i:s');
			self::connect()->exec("DELETE FROM `site_online` WHERE
				`last_action` < '$date' - INTERVAL 1 MINUTE");
		}

		public static function getOnlineUsers() {
			self::cleanOnlineUsers();
			$sql = self::connect()->prepare("SELECT ip, last_action FROM `site_online`");
			$sql->execute();
			return $sql->fetchAll();
		}

		public static function visit() {
			if (!isset($_COOKIE['visit'])) {
				setcookie('visit', 'true', time() + (60*60*24));
				$sql = self::connect()->prepare("INSERT INTO `site_visits` VALUES (null,?,?)");
				$sql->execute(array($_SERVER['REMOTE_ADDR'], date('Y-m-d')));
			}
		}

		public static function getTotalVisits() {
			$totalVisits = self::connect()->prepare("SELECT ip, day FROM `site_visits`");
			$totalVisits->execute();
			return $totalVisits->rowCount();
		}

		public static function getTodayVisits() {
			$todayVisits = self::connect()->prepare("SELECT ip, day FROM `site_visits` WHERE day = ?");
			$todayVisits->execute(array(date('Y-m-d')));
			return $todayVisits->rowCount();
		}

		public static function createProfile($name, $last_name, $email, $password, $avatar) {
			if ($avatar) {
				$validAvatar = Upload::validAvatar($avatar);
				if (!$validAvatar[0]) return $validAvatar;
			}

			$idExistent = true;
			while ($idExistent) {
				$id = uniqid();
				$sql = self::connect()->prepare("SELECT id FROM `panel_admins` WHERE id = ?");
				$sql->execute(array($id));
				if ($sql->rowCount() == 0) {
					$idExistent = false;
				}
			}

			$sql = self::connect()->prepare("INSERT INTO `panel_admins` (`id`, `name`, `last_name`, `email`, `password`) VALUES (?,?,?,?,?)");
			if (!$sql->execute(array($id, $name, $last_name, $email, $password)))
				return array(false, 'Falha ao cadastrar perfil.');

			if ($avatar) {
				$validAvatar = Upload::uploadAvatar($avatar, $id);
				if (!$validAvatar[0])
					return array(false, 'O perfil foi cadastrado, mas o upload da imagem falhou.');
				$sql = self::connect()->prepare("UPDATE `panel_admins` SET avatar = ? WHERE id = ?");
				if (!$sql->execute(array($validAvatar[1], $id))) {
					@unlink(DIR_AVATAR.$validAvatar[1]);
					return array(false, 'O perfil foi cadastrado, mas o upload da imagem falhou.');
				}
			}
			return array(true);
		}

		public static function getProfile() {
			$profile = self::connect()->prepare("SELECT name, last_name, email FROM `panel_admins` WHERE id = ?");
			$profile->execute(array($_SESSION['id']));
			return $profile->fetch();
		}

		public static function updateProfile($name, $last_name, $email, $password, $avatar) {
			if ($avatar) {
				$validAvatar = Upload::validAvatar($avatar);
				if (!$validAvatar[0]) return $validAvatar;
				$validAvatar = Upload::uploadAvatar($avatar);
				if (!$validAvatar[0]) return $validAvatar;

				$sql = self::connect()->prepare("UPDATE `panel_admins` SET avatar = ? WHERE id = ?");
				if (!$sql->execute(array($validAvatar[1], $_SESSION['id']))) {
					@unlink(DIR_AVATAR.$validAvatar[1]);
					return array(false, 'Falha ao atualizar campo AVATAR no banco de dados.');
				}
			}
			if ($name) {
				$sql = self::connect()->prepare("UPDATE `panel_admins` SET name = ? WHERE id = ?");
				if (!$sql->execute(array($name, $_SESSION['id'])))
					return array(false, 'Falha ao atualizar campo NOME no banco de dados.');
			}
			if ($last_name) {
				$sql = self::connect()->prepare("UPDATE `panel_admins` SET last_name = ? WHERE id = ?");
				if (!$sql->execute(array($last_name, $_SESSION['id'])))
					return array(false, 'Falha ao atualizar campo SOBRENOME no banco de dados.');
			}
			if ($email) {
				$sql = self::connect()->prepare("UPDATE `panel_admins` SET email = ? WHERE id = ?");
				if (!$sql->execute(array($email, $_SESSION['id'])))
					return array(false, 'Falha ao atualizar campo E-MAIL no banco de dados.');
			}
			if ($password) {
				$sql = self::connect()->prepare("UPDATE `panel_admins` SET password = ? WHERE id = ?");
				if (!$sql->execute(array($password, $_SESSION['id'])))
					return array(false, 'Falha ao atualizar campo SENHA no banco de dados.');
			}
			return array(true);
		}

		public static function addDeposition($name, $testimony, $date, $avatar) {
			if ($avatar) {
				$validAvatar = Upload::validAvatar($avatar);
				if (!$validAvatar[0]) return $validAvatar;

				$extension = explode('.', $avatar['name']);
				$avatarName = uniqid().'.'.$extension[count($extension) - 1];

				if (!move_uploaded_file($avatar['tmp_name'], DIR_DEPOSITIONS.$avatarName))
					return array(false, 'Falha no upload da imagem');
			} else
				$avatarName = null;

			$order = self::fetch("SELECT MAX(`order`) FROM `site_depositions`");
			$order[0]++;

			$sql = self::connect()->prepare("INSERT INTO `site_depositions` (`name`, `testimony`, `date`, `avatar`, `order`) VALUES (?,?,?,?,?)");
			if (!$sql->execute(array($name, $testimony, $date, $avatarName, $order[0]))) {
				@unlink(DIR_DEPOSITIONS.$avatarName);
				return array(false, 'Falha ao adicionar depoimento no banco de dados.');
			}
			return array(true);
		}

		public static function updateDeposition($id, $name, $testimony, $date, $avatar) {
			if ($avatar) {
				// Valida avatar
				$validAvatar = Upload::validAvatar($avatar);
				if (!$validAvatar[0]) return $validAvatar;

				// Apaga avatar existente
				$avatarName = self::fetch("SELECT `avatar` FROM `site_depositions` WHERE `id` = $id");
				if ($avatarName['avatar'])
					@unlink(DIR_DEPOSITIONS.$avatarName['avatar']);

				// Nome para o novo avatar
				$extension = explode('.', $avatar['name']);
				$avatarName = uniqid().'.'.$extension[count($extension) - 1];

				if (!move_uploaded_file($avatar['tmp_name'], DIR_DEPOSITIONS.$avatarName))
					return array(false, 'Falha no upload da imagem');

				$sql = self::connect()->prepare("UPDATE `site_depositions` SET `avatar` = ? WHERE `id` = ?");
				if (!$sql->execute(array($avatarName, $id))) {
					@unlink(DIR_DEPOSITIONS.$avatarName);
					return array(false, 'Falha no upload da imagem');
				}
			}

			if ($name) {
				$sql = self::connect()->prepare("UPDATE `site_depositions` SET `name` = ? WHERE `id` = ?");
				if (!$sql->execute(array($name, $id)))
					return array(false, 'Falha ao atualizar campo NOME no banco de dados.');
			}

			if ($testimony) {
				$sql = self::connect()->prepare("UPDATE `site_depositions` SET `testimony` = ? WHERE `id` = ?");
				if (!$sql->execute(array($testimony, $id)))
					return array(false, 'Falha ao atualizar campo DEPOIMENTO no banco de dados.');
			}

			if ($date) {
				$sql = self::connect()->prepare("UPDATE `site_depositions` SET `date` = ? WHERE `id` = ?");
				if (!$sql->execute(array($date, $id)))
					return array(false, 'Falha ao atualizar campo DATA no banco de dados.');
			}
			return array(true);
		}

		public static function addService($title, $about, $icon, $img) {
			if ($img) {
				$validImg = Upload::validAvatar($img);
				if (!$validImg[0]) return $validImg;

				$extension = explode('.', $img['name']);
				$imgName = uniqid().'.'.$extension[count($extension) - 1];

				if (!move_uploaded_file($img['tmp_name'], DIR_SERVICES.$imgName))
					return array(false, 'Falha no upload da imagem');
			} else
				$imgName = null;

			if (!$icon) $icon = null;

			$order = self::fetch("SELECT MAX(`order`) FROM `site_services`");
			$order[0]++;

			$sql = self::connect()->prepare("INSERT INTO `site_services` (`title`, `about`, `icon`, `img`, `order`) VALUES (?,?,?,?,?)");
			if (!$sql->execute(array($title, $about, $icon, $imgName, $order[0]))) {
				@unlink(DIR_SERVICES.$imgName);
				return array(false, 'Falha ao adicionar serviço no banco de dados.');
			}
			return array(true);
		}

		public static function updateService($id, $title, $about, $icon, $img) {
			if ($img) {
				// Valida imagem
				$validImg = Upload::validAvatar($img);
				if (!$validImg[0]) return $validImg;

				// Apaga imagem existente
				$imgName = self::fetch("SELECT `img` FROM `site_services` WHERE `id` = $id");
				if ($imgName['img'])
					@unlink(DIR_SERVICES.$imgName['img']);

				// Nome para a imagem nova
				$extension = explode('.', $img['name']);
				$imgName = uniqid().'.'.$extension[count($extension) - 1];

				if (!move_uploaded_file($img['tmp_name'], DIR_SERVICES.$imgName))
					return array(false, 'Falha no upload da imagem');

				$sql = self::connect()->prepare("UPDATE `site_services` SET `img` = ? WHERE `id` = ?");
				if (!$sql->execute(array($imgName, $id))) {
					@unlink(DIR_SERVICES.$imgName);
					return array(false, 'Falha no upload da imagem');
				}
			}

			if ($title) {
				$sql = self::connect()->prepare("UPDATE `site_services` SET `title` = ? WHERE `id` = ?");
				if (!$sql->execute(array($title, $id)))
					return array(false, 'Falha ao atualizar campo TÍTULO no banco de dados.');
			}

			if ($about) {
				$sql = self::connect()->prepare("UPDATE `site_services` SET `about` = ? WHERE `id` = ?");
				if (!$sql->execute(array($about, $id)))
					return array(false, 'Falha ao atualizar campo DESCRIÇÃO no banco de dados.');
			}

			if ($icon) {
				$sql = self::connect()->prepare("UPDATE `site_services` SET `icon` = ? WHERE `id` = ?");
				if (!$sql->execute(array($icon, $id)))
					return array(false, 'Falha ao atualizar campo ÍCONE no banco de dados.');
			}
			return array(true);
		}

		public static function addSlide($img) {
			$validImg = Upload::validAvatar($img);
			if (!$validImg[0]) return $validImg;
			
			$extension = explode('.', $img['name']);
			$imgName = uniqid().'.'.$extension[count($extension) - 1];

			if (!move_uploaded_file($img['tmp_name'], DIR_SLIDE.$imgName))
				return array(false, 'Falha no upload da imagem.');

			$order = self::fetch("SELECT MAX(`order`) FROM `site_slide`");
			$order[0]++;

			$sql = self::connect()->prepare("INSERT INTO `site_slide` (`img`, `order`) VALUES (?,?)");
			if (!$sql->execute(array($imgName, $order[0]))) {
				@unlink(DIR_SLIDE.$imgName);
				return array(false, 'Falha no upload da imagem.');
			}
			return array(true);
		}

		public static function updateSite($title, $description, $keywords, $favicon, $logo) {
			if ($title) {
				$sql = self::connect()->prepare("UPDATE `site_head` SET `title` = ?");
				if (!$sql->execute(array($title)))
					return array(false, 'Falha ao atualizar campo TÍTULO no banco de dados.');
			}

			if ($description) {
				$sql = self::connect()->prepare("UPDATE `site_head` SET `description` = ?");
				if (!$sql->execute(array($description)))
					return array(false, 'Falha ao atualizar campo DESCRIÇÃO no banco de dados.');
			}

			if ($keywords) {
				$sql = self::connect()->prepare("UPDATE `site_head` SET `keywords` = ?");
				if (!$sql->execute(array($keywords)))
					return array(false, 'Falha ao atualizar campo PALAVRAS-CHAVE no banco de dados.');
			}

			if ($logo) {
				// Valida imagem
				if ($logo['type'] != 'image/png')
					return array(false, 'A logo precisa ser PNG.');

				// Apaga imagem existente
				@unlink(DIR_SITE.'logo.png');

				if (!move_uploaded_file($logo['tmp_name'], DIR_SITE.'logo.png'))
					return array(false, 'Falha no upload da logo.');
			}

			if ($favicon) {
				// Valida imagem
				if ($favicon['type'] != 'image/vnd.microsoft.icon')
					return array(false, 'O favicon precisa ser ICO.');

				// Apaga imagem existente
				@unlink(DIR_SITE.'favicon.ico');

				if (!move_uploaded_file($favicon['tmp_name'], DIR_SITE.'favicon.ico'))
					return array(false, 'Falha no upload do favicon.');
			}
			return array(true);
		}

		public static function addCategory($name) {
			if (!$name)
				return array(false, 'O nome não pode ficar vazio.');
			$slug = Panel::generateSlug($name);
			if (self::rowCount("SELECT `slug` FROM `site_categories` WHERE `slug` = ?", array($slug)))
				return array(false, 'O nome escolhido já existe.');

			$position = self::position('site_categories');

			$sql = self::connect()->prepare("INSERT INTO `site_categories` (`name`, `slug`, `order`) VALUES (?,?,?)");
			if (!$sql->execute(array($name, $slug, $position)))
				return array(false, 'Falha ao inserir categoria no banco de dados.');
			return array(true);
		}

		public static function updateCategory($id, $name) {
			if (!$name)
				return array(false, 'O nome não pode ficar vazio.');

			$slug = Panel::generateSlug($name);
			if (self::rowCount("SELECT `slug` FROM `site_categories` WHERE `slug` = ?", array($slug)))
				return array(false, 'O nome escolhido já existe.');

			if (!self::update('site_categories', 'name', $name, $id) or
				!self::update('site_categories', 'slug', $slug, $id))
				return array(false, 'Falha ao atualizar categoria.');
			return array(true);
		}

		public static function addNotice($title, $date, $category, $content) {
			if (!$title or !$date or !$category or !$content)
				return array(false, 'Campos vazios não são permitidos.');

			$slug = Panel::generateSlug($title);
			if (self::rowCount("SELECT `slug` FROM `site_news` WHERE `slug` = ?", array($slug)))
				return array(false, 'O título escolhido já existe.');

			$sql = self::connect()->prepare("INSERT INTO `site_news` (`title`, `slug`, `date`, `category`, `content`) VALUES (?,?,?,?,?)");
			if (!$sql->execute(array($title, $slug, $date, $category, $content)))
				return array(false, 'Falha ao inserir notícia no banco de dados.');
			return array(true);
		}

		public static function updateNotice($id, $title, $date, $category, $content) {
			if ($title) {
				$slug = Panel::generateSlug($title);
				if (self::rowCount("SELECT `slug` FROM `site_news` WHERE `slug` = ?", array($slug)))
					return array(false, 'O título escolhido já existe.');

				if (!self::update('site_news', 'slug', $slug, $id))
					return array(false, 'Falha ao atualizar campo SLUG no banco de dados.');
				if (!self::update('site_news', 'title', $title, $id))
					return array(false, 'Falha ao atualizar campo TÍTULO no banco de dados.');
			}
			if ($date) {
				if (!self::update('site_news', 'date', $date, $id))
					return array(false, 'Falha ao atualizar campo DATA no banco de dados.');
			}
			if ($category) {
				if (!self::update('site_news', 'category', $category, $id))
					return array(false, 'Falha ao atualizar campo CATEGORIA no banco de dados.');
			}
			if ($content) {
				if (!self::update('site_news', 'content', $content, $id))
					return array(false, 'Falha ao atualizar campo CONTEÚDO no banco de dados.');
			}
			return array(true);
		}
	}
?>