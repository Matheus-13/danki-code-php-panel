<?php
	$courses = Database::selectAll('id, name', 'courses');
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Cadastrar Módulo</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/novo-modulo.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Descrição</label>
			<textarea name="about" required ></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Curso</label>
			<select name="course">
				<?php foreach ($courses as $value) { ?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
				<?php } ?>
			</select>
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Cadastrar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->