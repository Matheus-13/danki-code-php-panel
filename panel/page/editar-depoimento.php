<?php
	// Validação do parâmetro
	if (!isset($_GET['edit']) or $_GET['edit'] == '') {
		header('Location: '.PATH_PANEL.'listar-depoimentos');
		die();
	} else {
		$id = (int)$_GET['edit'];
		if (!Database::rowCount("SELECT `id` FROM `site_depositions` WHERE `id` = ?", array($id))) {
			header('Location: '.PATH_PANEL.'listar-depoimentos');
			die();
		}
	}
	
	if (isset($_POST['submit'])) {
		$name = isset($_POST['name']) ? $_POST['name'] : false;
		$testimony = isset($_POST['testimony']) ? $_POST['testimony'] : false;
		$date = isset($_POST['date']) ? $_POST['date'] : false;
		$avatar = ($_FILES['avatar']['type'] != '') ? $_FILES['avatar'] : false;

		// Atualiza o depoimento
		$updateDeposition = Database::updateDeposition($id, $name, $testimony, $date, $avatar);
		if ($updateDeposition[0])
			Panel::alert('success', 'O depoimento foi atualizado com sucesso.');
		else
			Panel::alert('error', $updateDeposition[1]);
	}

	$testimony = Database::fetch("SELECT `name`, `testimony`, `date`, `avatar` FROM `site_depositions` WHERE `id` = $id");
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Editar Depoimento</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Nome da Pessoa</label>
			<input type="text" name="name" value="<?php echo $testimony['name'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Depoimento</label>
			<textarea name="testimony"><?php echo $testimony['testimony'] ?></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Data</label>
			<input type="date" name="date" value="<?php echo $testimony['date'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<?php
			if ($testimony['avatar']) {
				$img = PATH_DEPOSITIONS.$testimony['avatar'];
				echo "<a href='$img' target='_blank'><img src='$img' /></a>";
			} ?>
			<input type="file" name="avatar" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->