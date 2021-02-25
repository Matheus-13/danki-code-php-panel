<?php
if (isset($_GET['edit']) and $_GET['edit'] != '') {
	if (Database::rowCount("SELECT `id` FROM `site_customers` WHERE `id` = ?", array($_GET['edit'])))
		$id = (int)$_GET['edit'];
}
?>

<?php
if (isset($id)) {
	$customer = Database::fetch("SELECT * FROM `site_customers` WHERE `id` = ?", array($id));
?>
<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Editar Cliente</h2>

	<form remove class="formPanel ajax" method="post" enctype="multipart/form-data" action="ajax/update-customer.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $customer['name']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>E-mail</label>
			<input type="email" name="email" value="<?php echo $customer['email']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Tipo</label>
			<select name="type">
				<option <?php if ($customer['type'] == 'physical') { echo 'selected'; } ?> value="physical">Físico</option>
				<option <?php if ($customer['type'] == 'legal') { echo 'selected'; } ?> value="legal">Jurídico</option>
			</select>
		</div><!--formBox-->
		<div class="formBox" ref="cpf" <?php if ($customer['type'] == 'legal') { echo 'style="display: none;"'; } ?> >
			<label>CPF</label>
			<input type="text" name="cpf" <?php if ($customer['type'] == 'physical') { echo "value='".$customer['cpf_cnpj']."'"; } ?> />
		</div><!--formBox-->
		<div class="formBox" ref="cnpj" <?php if ($customer['type'] == 'physical') { echo 'style="display: none;"'; } ?> >
			<label>CNPJ</label>
			<input type="text" name="cnpj" <?php if ($customer['type'] == 'legal') { echo "value='".$customer['cpf_cnpj']."'"; } ?> />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<?php
			if ($customer['img']) {
				echo "<a target='_blank' href='".PATH_CUSTOMERS.$customer['img']."'><img src='".PATH_CUSTOMERS.$customer['img']."' /></a>";
			}
			?>
			<input type="file" name="img" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="id" value="<?php echo $customer['id'] ?>" />
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>

	<h2><i class="fa fa-pencil"></i> Adicionar Pagamentos</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="ajax/add-payments.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Valor</label>
			<input type="text" name="value" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Número de parcelas</label>
			<input type="text" name="installments" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Intervalo</label>
			<input type="text" name="interval" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Vencimento</label>
			<input type="date" name="maturity" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="customer" value="<?php echo $customer['id'] ?>" />
			<input type="submit" name="submit" value="Inserir" />
		</div><!--formBox-->
	</form>

	<?php
	$pending = Database::fetchData("SELECT * FROM `panel_financial` WHERE `customer` = ? AND `status` = ? ORDER BY `maturity` ASC", false, false, array($customer['id'], 0));
	?>
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
				<span><?php echo $customer['name'] ?></span>
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

	<?php
	$paid = Database::fetchData("SELECT * FROM `panel_financial` WHERE `customer` = ? AND `status` = ? ORDER BY `maturity` ASC", false, false, array($customer['id'], 1));
	?>
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
				<span><?php echo $customer['name'] ?></span>
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

</div><!--contentBox-->
<?php } ?>

<?php
	$query = "";
	if (isset($_POST['submit']) and isset($_POST['search'])) {
		$search = $_POST['search'];
		$query = " WHERE `name` LIKE '%$search%' OR `email` LIKE '%$search%' OR `cpf_cnpj` LIKE '%$search%'";
	}

	$pages = Panel::pagination('site_customers', 20);

	if ($query == "")
		$customers = Database::fetchData("SELECT * FROM `site_customers`", ($pages['page'] - 1) * $pages['step'], $pages['step']);
	else
		$customers = Database::fetchAll("SELECT * FROM `site_customers` $query");
?>
<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Lista de Clientes</h2>

	<form class="formPanel" method="post">
		<div class="formBox">
			<label><i class="fa fa-search"></i> Realizar uma busca</label>
			<input type="text" name="search" placeholder="<?php if ($query == "") { echo 'Procure por: nome, email, cpf ou cnpj'; } else { echo count($customers).' resultado(s)'; } ?>" />
			<input type="submit" name="submit" value="Buscar" />
		</div><!--formBox-->
	</form>

	<div class="contactsContainer">
	<?php foreach ($customers as $value) { ?>
		<div class="contactBox">
			<header>
				<?php if ($value['img']) { ?>
					<a target="_blank" href="<?php echo PATH_CUSTOMERS.$value['img']; ?>"><img src="<?php echo PATH_CUSTOMERS.$value['img']; ?>" /></a>
				<?php } else { ?>
					<h2><i class="fa fa-user"></i></h2>
				<?php } ?>
			</header>
			<p><b>Nome: </b><?php echo $value['name'] ?></p>
			<p><b>E-mail: </b><?php echo $value['email'] ?></p>
			<?php if ($value['type'] == 'physical') { ?>
				<p><b>Tipo: </b>Físico</p>
				<p><b>CPF: </b><?php echo $value['cpf_cnpj']; ?></p>
			<?php } else { ?>
				<p><b>Tipo: </b>Jurídico</p>
				<p><b>CNPJ: </b><?php echo $value['cpf_cnpj']; ?></p>
			<?php } ?>
			<span><a href='?edit=<?php echo $value["id"]; ?>'><i class='fa fa-pencil bgOrange'></i></a></span>
			<span><a delete='ajax' id='<?php echo $value["id"]; ?>' href><i class='fa fa-times bgRed'></i></a></span>
			<div class="clear"></div>
		</div><!--contactBox-->
	<?php } ?>
	</div><!--contactsContainer-->

	<div class="pagination">
	<?php
	if ($query == "")
		Panel::printPagination($pages['page'], $pages['total']);
	?>
	</div><!--pagination-->

</div><!--contentBox-->

<const page='customers' />