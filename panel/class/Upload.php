<?php
	class Upload
	{
		public static function validAvatar($avatar) {
			if ($avatar['type'] == 'image/jpeg' ||
				$avatar['type'] == 'image/jpg' ||
				$avatar['type'] == 'image/png') {

				$size = intval($avatar['size'] / 1024);
				if ($size < 300)
					return array(true);
				else
					return array(false, 'O tamanho máximo da imagem é 300KB.');
			} else {
				return array(false, 'A imagem precisa ser JPG ou PNG.');
			}
		}

		public static function uploadAvatar($avatar, $id = false) {
			if (!$id) $id = $_SESSION['id'];

			@unlink(DIR_AVATAR.$id.'.jpeg');
			@unlink(DIR_AVATAR.$id.'.jpg');
			@unlink(DIR_AVATAR.$id.'.png');

			$format = explode('.', $avatar['name']);
			$name = $id.'.'.$format[count($format) - 1];

			if (move_uploaded_file($avatar['tmp_name'], DIR_AVATAR.$name))
				return array(true, $name);
			else
				return array(false, 'Falha ao copiar imagem para o servidor.');
		}

		public static function imgName($img) {
			$extension = explode('.', $img['name']);
			$name = uniqid().'.'.$extension[count($extension) - 1];
			return $name;
		}
	}
?>