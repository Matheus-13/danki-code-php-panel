<?php
	$courses = Database::selectAll('id, name', 'courses');
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Cadastrar Aula</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/nova-aula.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Link</label>
			<textarea name="link" required ></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Módulo</label>
			<select name="module">
				<?php foreach ($courses as $value) {
					$modules = Database::selectAll('id, name', 'modules', 'course_id = '.$value['id']);
				?>
					<option disabled>----- <?php echo $value['name']; ?> -----</option>
					<?php foreach ($modules as $value2) { ?>
					<option value="<?php echo $value2['id']; ?>"><?php echo $value2['name']; ?></option>
					<?php }
				} ?>
			</select>
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Cadastrar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->