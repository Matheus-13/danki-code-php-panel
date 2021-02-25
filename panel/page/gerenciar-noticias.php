<?php
if (isset($_GET['delete'])) {
	$id = (int)$_GET['delete'];
	if (!Database::rowCount("SELECT `id` FROM `site_news` WHERE `id` = ?", array($id)))
		Panel::alert('error', 'A notícia selecionada é inválida.');

	else {
		if (!Database::delete('site_comments', 'news_id', $id)) {
			Panel::alert('error', 'Falha ao apagar comentários.');

		} else if (!Database::delete('site_news', 'id', $id)) {
			Panel::alert('error', 'Falha ao apagar notícia.');
		}
	}
}

$step = 20;
$totalPages = ceil(Database::rowCount("SELECT `id` FROM `site_news`") / $step);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
else if ($page > $totalPages) $page = $totalPages;

$categories = Database::fetchArray("SELECT `id`, `name` FROM `site_categories`");
$news = Database::fetchData("SELECT `id`, `title`, `category`, `date` FROM `site_news` ORDER BY `date`", ($page - 1) * $step, $step);
?>

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Lista de Notícias</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Título</span>
			</div>
			<div class="col">
				<span>Categoria</span>
			</div>
			<div class="col">
				<span>Data</span>
			</div>
			<div class="col">
				<span>Editar</span>
			</div>
			<div class="col">
				<span>Excluir</span>
			</div>
		</div><!--row-->

		<?php
		foreach ($news as $key => $value) {
			echo "<div class='row'>
			<div class='col'>
				<span>".$value['title']."</span>
			</div>
			<div class='col'>
				<span>".$categories[$value['category']][0]."</span>
			</div>
			<div class='col'>
				<span>".$value['date']."</span>
			</div>
			<div class='col'>
				<span><a href='".PATH_PANEL."editar-noticia?edit=".$value['id']."'><i class='fa fa-pencil bgOrange'></i></a></span>
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