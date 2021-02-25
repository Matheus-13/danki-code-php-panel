<?php
	if (isset($_POST['submit']) and $_FILES['img']['type'] != '') {
		$addSlide = Database::addSlide($_FILES['img']);
		if ($addSlide[0])
			Panel::alert('success', 'Imagem adicionada com sucesso.');
		else
			Panel::alert('error', $addSlide[1]);

	} else if (isset($_GET['delete'])) {
		$id = (int)$_GET['delete'];
		if (!Database::rowCount("SELECT `id` FROM `site_slide` WHERE `id` = ?", array($id)))
			Panel::alert('error', 'A imagem selecionada é inválida.');
		else {
			// Apaga imagem
			$img = Database::fetch("SELECT `img` FROM `site_slide` WHERE `id` = $id");
			@unlink(DIR_SLIDE.$img['img']);
			if (!Database::delete('site_slide', 'id', $id))
				Panel::alert('error', 'Falha ao apagar imagem.');
		}

	} else if (isset($_GET['up'])) {
		$id = (int)$_GET['up'];
		$order = Database::order('up', 'site_slide', $id);
		if (!$order[0]) Panel::alert('error', $order[1]);

	} else if (isset($_GET['down'])) {
		$id = (int)$_GET['down'];
		$order = Database::order('down', 'site_slide', $id);
		if (!$order[0]) Panel::alert('error', $order[1]);
	}

	$step = 10;
	$totalPages = ceil(Database::rowCount("SELECT `id` FROM `site_slide`") / $step);

	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	if ($page < 1) $page = 1;
	else if ($page > $totalPages) $page = $totalPages;
	
	$images = Database::fetchData("SELECT `id`, `img` FROM `site_slide` ORDER BY `order`", ($page - 1) * $step, $step);
?>

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Adicionar Slide</h2>

	<form class="formPanel" method="post" enctype="multipart/form-data">
		<div class="formBox">
			<label>Imagem</label>
			<input type="file" name="img" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Adicionar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Slides Adicionados</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Nome</span>
			</div>
			<div class="col">
				<span>Imagem</span>
			</div>
			<div class="col">
				<span>Sobe</span>
			</div>
			<div class="col">
				<span>Desce</span>
			</div>
			<div class="col">
				<span>Excluir</span>
			</div>
		</div><!--row-->
<a href="" target="blan"></a>
		<?php
		foreach ($images as $key => $value) {
			echo "<div class='row'>
			<div class='col'>
				<span>".$value['img']."</span>
			</div>
			<div class='col'>
				<a target='_blank' href='".PATH_SLIDE.$value['img']."'><img src='".PATH_SLIDE.$value['img']."' /></a>
			</div>
			<div class='col'>
				<span><a href='?up=".$value['id']."'><i class='fa fa-angle-up bgBlue'></i></a></span>
			</div>
			<div class='col'>
				<span><a href='?down=".$value['id']."'><i class='fa fa-angle-down bgBlue'></i></a></span>
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