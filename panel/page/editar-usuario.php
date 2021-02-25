<?php
	if (isset($_POST['submit'])) {
		$name = isset($_POST['name']) ? $_POST['name'] : false;
		$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : false;
		$email = isset($_POST['email']) ? $_POST['email'] : false;
		$password = isset($_POST['password']) ? $_POST['password'] : false;
		$avatar = ($_FILES['avatar']['type'] != '') ? $_FILES['avatar'] : false;

		$updateProfile = Database::updateProfile($name, $last_name, $email, $password, $avatar);
		if ($updateProfile[0]) {
			Panel::alert('success', 'Perfil atualizado com sucesso.');
		} else {
			Panel::alert('error', $updateProfile[1]);
		}
	}

	$profile = Database::getProfile();
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Editar Usuário</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $profile['name'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Sobrenome</label>
			<input type="text" name="last_name" value="<?php echo $profile['last_name'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>E-mail</label>
			<input type="email" name="email" value="<?php echo $profile['email'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Senha</label>
			<input type="password" name="password" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<input type="file" name="avatar" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->