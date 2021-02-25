<?php
if (isset($_POST['submit'])) {
	$addNotice = Database::addNotice($_POST['title'], $_POST['date'], $_POST['category'], $_POST['content']);
	if ($addNotice[0])
		Panel::alert('success', 'Notícia adicionada com sucesso.');
	else
		Panel::alert('error', $addNotice[1]);
}

$categories = Database::fetchAll("SELECT `id`, `name` FROM `site_categories`");
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Adicionar Notícia</h2>

	<form class="formPanel" method="post">
		<div class="formBox">
			<label>Título</label>
			<input type="text" name="title" value="<?php Panel::recoverPost('title') ?>" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Data</label>
			<input type="date" name="date" value="<?php Panel::recoverPost('date') ?>" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Categoria</label>
			<select name="category">
				<?php foreach ($categories as $key => $value) { ?>
					<option <?php if (isset($_POST['category']) and $_POST['category'] == $value['id']) echo 'selected'; ?> value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
				<?php } ?>
			</select>
		</div><!--formBox-->
		<div class="formBox">
			<label>Conteúdo</label>
			<textarea class="editor" name="content"><?php Panel::recoverPost('content') ?></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Adicionar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->
<const page='news' />