<style>
	*{
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	h2{
		background: #333;
		color: white;
		padding: 8px;
	}
	.box{
		width: 900px;
		margin:0 auto;
	}

	table{
		width: 900px;
		margin-top:15px;
		border-collapse: collapse;
	}
	table td{
		font-size: 14px;
		padding:8px;
		border: 1px solid #ccc;
	}
</style>

<div class="box">

<?php
if ($_GET['payments'] == 'paid') {
	$name = 'Concluídos';
	$status = 1;

} else {
	$name = 'Pendentes';
	$status = 0;
}
?>

<h2><i class="fa fa-id-card-o"></i> Pagamentos <?php echo $name; ?></h2>
	<div class="wraper-table">
	<table>
		<tr>
			<td style="font-weight: bold;">Nome</td>
			<td style="font-weight: bold;">Cliente</td>
			<td style="font-weight: bold;">Valor</td>
			<td style="font-weight: bold;">Vencimento</td>
		</tr>

		<?php
			$customers = Database::fetchArray("SELECT `id`, `name` FROM `site_customers`");
			$payments = Database::fetchAll("SELECT * FROM `panel_financial` WHERE `status` = $status ORDER BY `maturity` ASC");

			foreach ($payments as $key => $value) {
		?>
		<tr>
			<td><?php echo $value['name']; ?></td>
			<td><?php echo $customers[$value['customer']][0]; ?></td>
			<td><?php echo $value['value']; ?></td>
			<td><?php echo date('d/m/Y', strtotime($value['maturity'])); ?></td>
		</tr>
		<?php } ?>

	</table>
	</div>

</div>