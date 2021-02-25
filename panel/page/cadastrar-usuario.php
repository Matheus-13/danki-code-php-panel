<?php
	if (isset($_POST['submit'])) {
		$name = isset($_POST['name']) ? $_POST['name'] : false;
		$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : false;
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? $_POST['password'] : false;

		if (!$name or !$last_name or !$email or !$password) {
			Panel::alert('error', 'Apenas o campo IMAGEM pode ficar vazio.');
		} else {
			$avatar = ($_FILES['avatar']['type'] != '') ? $_FILES['avatar'] : false;
			$createProfile = Database::createProfile($name, $last_name, $email, $password, $avatar);
			if ($createProfile[0]) {
				Panel::alert('success', 'Perfil cadastrado com sucesso.');
			} else {
				Panel::alert('error', $createProfile[1]);
			}
		}
	}
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Cadastrar Usuário</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Sobrenome</label>
			<input type="text" name="last_name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>E-mail</label>
			<input type="email" name="email" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Senha</label>
			<input type="password" name="password" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<input type="file" name="avatar" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Cadastrar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->