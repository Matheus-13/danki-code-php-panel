<?php
	if (isset($_GET['delete'])) {
		$id = (int)$_GET['delete'];
		if (!Database::rowCount("SELECT `id` FROM `site_depositions` WHERE `id` = ?", array($id)))
			Panel::alert('error', 'O depoimento selecionado é inválido.');
		else {
			// Apaga imagem e depoimento
			$avatar = Database::fetch("SELECT `avatar` FROM `site_depositions` WHERE `id` = $id");
			if ($avatar['avatar'])
				@unlink(DIR_DEPOSITIONS.$avatar['avatar']);
			if (!Database::delete('site_depositions', 'id', $id))
				Panel::alert('error', 'Falha ao apagar depoimento.');
		}

	} else if (isset($_GET['up'])) {
		$id = (int)$_GET['up'];
		$order = Database::order('up', 'site_depositions', $id);
		if (!$order[0]) Panel::alert('error', $order[1]);

	} else if (isset($_GET['down'])) {
		$id = (int)$_GET['down'];
		$order = Database::order('down', 'site_depositions', $id);
		if (!$order[0]) Panel::alert('error', $order[1]);
	}

	$step = 10;
	$totalPages = ceil(Database::rowCount("SELECT `id` FROM `site_depositions`") / $step);

	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	if ($page < 1) $page = 1;
	else if ($page > $totalPages) $page = $totalPages;
	
	$depositions = Database::fetchData("SELECT `id`, `name`, `date` FROM `site_depositions` ORDER BY `order`", ($page - 1) * $step, $step);
?>

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Depoimentos Cadastrados</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Nome</span>
			</div>
			<div class="col">
				<span>Data</span>
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
		foreach ($depositions as $key => $value) {
			echo "<div class='row'>
			<div class='col'>
				<span>".$value['name']."</span>
			</div>
			<div class='col'>
				<span>".$value['date']."</span>
			</div>
			<div class='col'>
				<span><a href='?up=".$value['id']."'><i class='fa fa-angle-up bgBlue'></i></a></span>
			</div>
			<div class='col'>
				<span><a href='?down=".$value['id']."'><i class='fa fa-angle-down bgBlue'></i></a></span>
			</div>
			<div class='col'>
				<span><a href='".PATH_PANEL."editar-depoimento?edit=".$value['id']."'><i class='fa fa-pencil bgOrange'></i></a></span>
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