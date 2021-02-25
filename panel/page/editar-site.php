<?php	
if (isset($_POST['submit'])) {
	$title = isset($_POST['title']) ? $_POST['title'] : false;
	$description = isset($_POST['description']) ? $_POST['description'] : false;
	$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : false;
	$favicon = ($_FILES['favicon']['type'] != '') ? $_FILES['favicon'] : false;
	$logo = ($_FILES['logo']['type'] != '') ? $_FILES['logo'] : false;

	$updateSite = Database::updateSite($title, $description, $keywords, $favicon, $logo);
	if ($updateSite[0])
		Panel::alert('success', 'Atualizado com sucesso.');
	else
		Panel::alert('error', $updateSite[1]);
}

$site = Database::fetch("SELECT `title`, `description`, `keywords` FROM `site_head`");
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Editar Site</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Título do Site</label>
			<input type="text" name="title" value="<?php echo $site['title'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Descrição do Site</label>
			<textarea name="description"><?php echo $site['description'] ?></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Palavras-Chave</label>
			<textarea name="keywords"><?php echo $site['keywords'] ?></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Favicon</label>
			<?php
			echo "<a target='_blank' href='".PATH_SITE.'favicon.ico'."'><img src='".PATH_SITE.'favicon.ico'."' /></a>";
			?>
			<input type="file" name="favicon" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Logo</label>
			<?php
			echo "<a target='_blank' href='".PATH_SITE.'logo.png'."'><img src='".PATH_SITE.'logo.png'."' /></a>";
			?>
			<input type="file" name="logo" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->