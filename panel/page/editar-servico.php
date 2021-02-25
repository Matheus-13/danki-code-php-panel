<?php
	// Validação do parâmetro
	if (!isset($_GET['edit']) or $_GET['edit'] == '') {
		header('Location: '.PATH_PANEL.'listar-servicos');
		die();
	} else {
		$id = (int)$_GET['edit'];
		if (!Database::rowCount("SELECT `id` FROM `site_services` WHERE `id` = ?", array($id))) {
			header('Location: '.PATH_PANEL.'listar-servicos');
			die();
		}
	}
	
	if (isset($_POST['submit'])) {
		$title = isset($_POST['title']) ? $_POST['title'] : false;
		$about = isset($_POST['about']) ? $_POST['about'] : false;
		$icon = isset($_POST['icon']) ? $_POST['icon'] : false;
		$img = ($_FILES['img']['type'] != '') ? $_FILES['img'] : false;

		// Atualiza o serviço
		$updateService = Database::updateService($id, $title, $about, $icon, $img);
		if ($updateService[0])
			Panel::alert('success', 'O serviço foi atualizado com sucesso.');
		else
			Panel::alert('error', $updateService[1]);
	}

	$service = Database::fetch("SELECT `title`, `about`, `icon`, `img` FROM `site_services` WHERE `id` = $id");
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Editar Serviço</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Título</label>
			<input type="text" name="title" value="<?php echo $service['title'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Descrição</label>
			<textarea name="about"><?php echo $service['about'] ?></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Código do Ícone</label>
			<input type="text" name="icon" value="<?php echo $service['icon'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<?php
			if ($service['img']) {
				$img = PATH_SERVICES.$service['img'];
				echo "<a href='$img' target='_blank'><img src='$img' /></a>";
			} ?>
			<input type="file" name="img" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->