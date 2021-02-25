<?php
	if (isset($_POST['submit'])) {
		$name = isset($_POST['name']) ? $_POST['name'] : false;
		$testimony = isset($_POST['testimony']) ? $_POST['testimony'] : false;
		$date = isset($_POST['date']) ? $_POST['date'] : false;

		if (!$name or !$testimony or !$date) {
			Panel::alert('error', 'Apenas o campo IMAGEM pode ficar vazio.');
		} else {
			$avatar = ($_FILES['avatar']['type'] != '') ? $_FILES['avatar'] : false;
			$addDeposition = Database::addDeposition($name, $testimony, $date, $avatar);

			if ($addDeposition[0]) {
				Panel::alert('success', 'Depoimento adicionado com sucesso.');
			} else {
				Panel::alert('error', $addDeposition[1]);
			}
		}
	}
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Adicionar Depoimento</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Nome da Pessoa</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Depoimento</label>
			<textarea name="testimony" required ></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Data</label>
			<input type="date" name="date" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<input type="file" name="avatar" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Adicionar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->