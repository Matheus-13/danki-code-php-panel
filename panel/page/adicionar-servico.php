<?php
	if (isset($_POST['submit'])) {
		$title = isset($_POST['title']) ? $_POST['title'] : false;
		$about = isset($_POST['about']) ? $_POST['about'] : false;
		if (!$title or !$about) {
			Panel::alert('error', 'TÍTULO e DESCRIÇÃO são obrigatórios.');

		} else {
			$icon = isset($_POST['icon']) ? $_POST['icon'] : false;
			$img = ($_FILES['img']['type'] != '') ? $_FILES['img'] : false;

			$addService = Database::addService($title, $about, $icon, $img);
			if ($addService[0])
				Panel::alert('success', 'Serviço adicionado com sucesso.');
			else
				Panel::alert('error', $addService[1]);
		}
	}
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Adicionar Serviço</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Título</label>
			<input type="text" name="title" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Descreva o Serviço</label>
			<textarea name="about" required ></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Código do Ícone</label>
			<input type="text" name="icon" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<input type="file" name="img" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Adicionar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->