<?php
if (isset($_POST['submit']) and isset($_POST['add'])) {
	$addCategory = Database::addCategory($_POST['name']);
	if ($addCategory[0])
		Panel::alert('success', 'Categoria adicionada com sucesso.');
	else
		Panel::alert('error', $addCategory[1]);

} else if (isset($_GET['edit']) and $_GET['edit'] != '') {
	if (!Database::rowCount("SELECT `id` FROM `site_categories` WHERE `id` = ?", array($_GET['edit'])))
		Panel::alert('error', 'A categoria selecionada é inválida.');

	else {
		$id = (int)$_GET['edit'];
		if (isset($_POST['submit']) and isset($_POST['update'])) {
			$updateCategory = Database::updateCategory($id, $_POST['name']);
			if ($updateCategory[0])
				Panel::alert('success', 'Categoria atualizada com sucesso.');
			else
				Panel::alert('error', $updateCategory[1]);
		}
	}

} else if (isset($_GET['delete'])) {
	if (!Database::rowCount("SELECT `id` FROM `site_categories` WHERE `id` = ?", array($_GET['delete'])))
		Panel::alert('error', 'A categoria selecionada é inválida.');

	else {
		$news = Database::selectAll('id', 'site_news', 'category = '.$_GET['delete']);
		foreach ($news as $value) {
			Database::delete('site_comments', 'news_id', $value['id']);
		}

		if (!Database::delete('site_news', 'category', $_GET['delete']))
			Panel::alert('error', 'Falha ao apagar as notícias.');

		if (!Database::delete('site_categories', 'id', $_GET['delete']))
			Panel::alert('error', 'Falha ao apagar as categorias.');
	}

} else if (isset($_GET['up'])) {
	$order = Database::order('up', 'site_categories', (int)$_GET['up']);
	if (!$order[0]) Panel::alert('error', $order[1]);

} else if (isset($_GET['down'])) {
	$order = Database::order('down', 'site_categories', (int)$_GET['down']);
	if (!$order[0]) Panel::alert('error', $order[1]);
}

$step = 10;
$totalPages = ceil(Database::rowCount("SELECT `id` FROM `site_categories`") / $step);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
else if ($page > $totalPages) $page = $totalPages;
?>

<?php if (isset($id)) {
	$category = Database::fetch("SELECT `name` FROM `site_categories` WHERE `id` = ?", array($id));
?>
<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Editar Categoria</h2>

	<form class="formPanel" method="post">
		<div class="formBox">
			<label>Nome Novo</label>
			<input type="text" name="name" value="<?php echo $category['name'] ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="update" />
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->
<?php } ?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Adicionar Categoria</h2>

	<form class="formPanel" method="post">
		<div class="formBox">
			<label>Nome da Categoria</label>
			<input type="text" name="name" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="add" />
			<input type="submit" name="submit" value="Adicionar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->

<?php
$categories = Database::fetchData("SELECT `id`, `name`, `slug` FROM `site_categories` ORDER BY `order`", ($page - 1) * $step, $step);
?>
<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Categorias Adicionadas</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Nome</span>
			</div>
			<div class="col">
				<span>Slug</span>
			</div>
			<div class="col">
				<span>Sobe</span>
			</div>
			<div class="col">
				<span>Desce</span>
			</div>
			<div class="col">
				<span>Editar</span>
			</div>
			<div class="col">
				<span>Excluir</span>
			</div>
		</div><!--row-->

		<?php
		foreach ($categories as $key => $value) {
			echo "<div class='row'>
			<div class='col'>
				<span>".$value['name']."</span>
			</div>
			<div class='col'>
				<span>".$value['slug']."</span>
			</div>
			<div class='col'>
				<span><a href='?up=".$value['id']."'><i class='fa fa-angle-up bgBlue'></i></a></span>
			</div>
			<div class='col'>
				<span><a href='?down=".$value['id']."'><i class='fa fa-angle-down bgBlue'></i></a></span>
			</div>
			<div class='col'>
				<span><a href='?edit=".$value['id']."'><i class='fa fa-pencil bgOrange'></i></a></span>
			</div>
			<div class='col'>
				<span><a delete='true' href='?delete=".$value['id']."'><i class='fa fa-times bgRed'></i></a></span>
			</div>
			</div><!--row-->";
		}
		?>
	</div><!--responsiveTable-->

	<div class="pagination">
	<?php
	for ($i = 1; $i <= $totalPages; $i++) {
		if ($i == $page)
			echo "<a class='active' href='?page=".$i."'>".$i."</a>";
		else
			echo "<a href='?page=".$i."'>".$i."</a>";
	}
	?>
	</div><!--pagination-->

</div><!--contentBox-->