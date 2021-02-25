<?php
	$tasks = Database::selectAll('*', 'panel_schedule');
?>

<div class="contentBox">
	<h2><i class="fas fa-calendar"></i> Agenda</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Tarefa</span>
			</div>
			<div class="col">
				<span>Data</span>
			</div>
			<div class="col">
				<span>Excluir</span>
			</div>
		</div><!--row-->
		<?php foreach ($tasks as $value) { ?>
		<div class='row'>
			<div class='col'>
				<span><?php echo $value['task']; ?></span>
			</div>
			<div class='col'>
				<span><?php echo date('d/m/Y', strtotime($value['date'])); ?></span>
			</div>
			<div class='col'>
				<span><a delete='ajax' id='<?php echo $value["id"]; ?>' href><i class='fa fa-calendar-times bgRed'></i></a></span>
			</div>
		</div><!--row-->
		<?php } ?>
	</div><!--responsiveTable-->

</div><!--contentBox-->

<div class="contentBox">
	<h2><i class="far fa-calendar-plus"></i> Adicionar Tarefa</h2>

	<form class="formPanel ajax" method="post" action="<?php echo PATH_PANEL ?>ajax/agenda.php">
		<div class="formBox">
			<label>Tarefa</label>
			<input type="text" name="task" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Data</label>
			<input type="date" name="date" required />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Adicionar" />
		</div><!--formBox-->
	</form>

</div><!--contentBox-->
<const page='schedule' />