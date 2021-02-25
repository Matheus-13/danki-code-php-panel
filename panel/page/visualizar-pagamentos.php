<?php
$customers = Database::fetchArray("SELECT `id`, `name` FROM `site_customers`");

$pending = Database::fetchData("SELECT * FROM `panel_financial` WHERE `status` = ? ORDER BY `maturity` ASC", false, false, array(0));
?>
<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Pagamentos Pendentes</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Nome</span>
			</div>
			<div class="col">
				<span>Cliente</span>
			</div>
			<div class="col">
				<span>Valor</span>
			</div>
			<div class="col">
				<span>Vencimento</span>
			</div>
			<div class="col">
				<span>Marcar como pago</span>
			</div>
		</div><!--row-->

		<?php
		$today = strtotime(date('Y-m-d'));
		foreach ($pending as $key => $value) {
		?>
			<div class="row <?php if ($today >= strtotime($value['maturity'])) { echo 'overdue'; } ?>">
			<div class='col'>
				<span><?php echo $value['name'] ?></span>
			</div>
			<div class='col'>
				<span><?php echo $customers[$value['customer']][0] ?></span>
			</div>
			<div class='col'>
				<span><?php echo $value['value'] ?></span>
			</div>
			<div class='col'>
				<span><?php echo date('d/m/Y', strtotime($value['maturity'])) ?></span>
			</div>
			<div class='col'>
				<span><a paid='true' id='<?php echo $value["id"]; ?>' href><i class='fa fa-check bgGreen'></i></a></span>
			</div>
			</div><!--row-->
		<?php } ?>
	</div><!--responsiveTable-->
	<div class="formPanel formBox">
		<a target="_blank" href="<?php echo PATH_PANEL.'page/imprimir.php?payments=pending'; ?>"><input type="submit" value="Imprimir" /></a>
	</div>

</div><!--contentBox-->

<?php
$paid = Database::fetchData("SELECT * FROM `panel_financial` WHERE `status` = ? ORDER BY `maturity` ASC", false, false, array(1));
?>
<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Pagamentos Concluídos</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Nome</span>
			</div>
			<div class="col">
				<span>Cliente</span>
			</div>
			<div class="col">
				<span>Valor</span>
			</div>
			<div class="col">
				<span>Vencimento</span>
			</div>
		</div><!--row-->

		<?php
		foreach ($paid as $key => $value) {
		?>
			<div class="row">
			<div class='col'>
				<span><?php echo $value['name'] ?></span>
			</div>
			<div class='col'>
				<span><?php echo $customers[$value['customer']][0] ?></span>
			</div>
			<div class='col'>
				<span><?php echo $value['value'] ?></span>
			</div>
			<div class='col'>
				<span><?php echo date('d/m/Y', strtotime($value['maturity'])) ?></span>
			</div>
			</div><!--row-->
		<?php } ?>
	</div><!--responsiveTable-->
	<div class="formPanel formBox">
		<a target="_blank" href="<?php echo PATH_PANEL.'page/imprimir.php?payments=paid'; ?>"><input type="submit" value="Imprimir" /></a>
	</div>

</div><!--contentBox-->
<const page='payments' />