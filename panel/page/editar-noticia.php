<?php
// Validação do parâmetro
if (!isset($_GET['edit']) or $_GET['edit'] == '') {
	header('Location: '.PATH_PANEL.'gerenciar-noticias');
	die();
} else {
	$id = (int)$_GET['edit'];
	if (!Database::rowCount("SELECT `id` FROM `site_news` WHERE `id` = ?", array($id))) {
		header('Location: '.PATH_PANEL.'gerenciar-noticias');
		die();
	}
}

if (isset($_POST['submit'])) {
	$title = isset($_POST['title']) ? $_POST['title'] : false;
	$date = isset($_POST['date']) ? $_POST['date'] : false;
	$category = isset($_POST['category']) ? $_POST['category'] : false;
	$content = isset($_POST['content']) ? $_POST['content'] : false;

	// Atualiza a notícia
	$updateNotice = Database::updateNotice($id, $title, $date, $category, $content);
	if ($updateNotice[0])
		Panel::alert('success', 'A notícia foi atualizada com sucesso.');
	else
		Panel::alert('error', $updateNotice[1]);
}

$categories = Database::fetchAll("SELECT `id`, `name` FROM `site_categories`");
$notice = Database::fetch("SELECT `title`, `date`, `category`, `content` FROM `site_news` WHERE `id` = $id");
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Editar Notícia</h2>

	<form class="formPanel" method="post">
		<div class="formBox">
			<label>Título</label>
			<input type="text" name="title" value="<?php Panel::recoverPost('title', $notice['title']) ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Data</label>
			<input type="date" name="date" value="<?php Panel::recoverPost('date', $notice['date']) ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Categoria</label>
			<select name="category">
				<?php foreach ($categories as $key => $value) { ?>
					<option <?php if ($notice['category'] == $value['id']) echo 'selected'; ?> value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
				<?php } ?>
			</select>
		</div><!--formBox-->
		<div class="formBox">
			<label>Conteúdo</label>
			<textarea class="editor" name="content"><?php Panel::recoverPost('content', $notice['content']) ?></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->
<const page='news' />