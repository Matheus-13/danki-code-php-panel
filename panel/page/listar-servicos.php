<?php
	if (isset($_GET['delete'])) {
		$id = (int)$_GET['delete'];
		if (!Database::rowCount("SELECT `id` FROM `site_services` WHERE `id` = ?", array($id)))
			Panel::alert('error', 'O serviço selecionado é inválido.');
		else {
			// Apaga imagem e serviço
			$img = Database::fetch("SELECT `img` FROM `site_services` WHERE `id` = $id");
			if ($img['img'])
				@unlink(DIR_SERVICES.$img['img']);
			if (!Database::delete('site_services', 'id', $id))
				Panel::alert('error', 'Falha ao apagar serviço.');
		}

	} else if (isset($_GET['up'])) {
		$id = (int)$_GET['up'];
		$order = Database::order('up', 'site_services', $id);
		if (!$order[0]) Panel::alert('error', $order[1]);

	} else if (isset($_GET['down'])) {
		$id = (int)$_GET['down'];
		$order = Database::order('down', 'site_services', $id);
		if (!$order[0]) Panel::alert('error', $order[1]);
	}

	$step = 10;
	$totalPages = ceil(Database::rowCount("SELECT `id` FROM `site_services`") / $step);

	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	if ($page < 1) $page = 1;
	else if ($page > $totalPages) $page = $totalPages;
	
	$services = Database::fetchData("SELECT `id`, `title` FROM `site_services` ORDER BY `order`", ($page - 1) * $step, $step);
?>

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Serviços Cadastrados</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Título</span>
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
		foreach ($services as $key => $value) {
			echo "<div class='row'>
			<div class='col'>
				<span>".$value['title']."</span>
			</div>
			<div class='col'>
				<span><a href='?up=".$value['id']."'><i class='fa fa-angle-up bgBlue'></i></a></span>
			</div>
			<div class='col'>
				<span><a href='?down=".$value['id']."'><i class='fa fa-angle-down bgBlue'></i></a></span>
			</div>
			<div class='col'>
				<span><a href='".PATH_PANEL."editar-servico?edit=".$value['id']."'><i class='fa fa-pencil bgOrange'></i></a></span>
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