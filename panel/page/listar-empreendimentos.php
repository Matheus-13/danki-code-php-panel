<?php
$id = isset($_GET['edit']) ? (int)$_GET['edit'] : false;
$propertyID = isset($_GET['property']) ? (int)$_GET['property'] : false;
if ($propertyID and Database::exist('enterprise_properties', $propertyID)) {
	$property = Database::select('*', 'enterprise_properties', 'id = '.$propertyID);
?>

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Editar Imóvel <?php echo $propertyID; ?></h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/atualizar-imovel.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $property['name']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Preço</label>
			<input type="text" name="price" value="<?php echo Convert::currencyBr($property['price']); ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Área (m²)</label>
			<input type="number" name="area" value="<?php echo $property['area']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Selecione as imagens</label>
			<input type="file" name="img[]" multiple />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="id" value="<?php echo $propertyID; ?>" />
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>

	<?php $propertyImgs = Database::selectAll('id, img', 'property_images', 'propertyID = '.$propertyID); ?>

	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Imagens do Imóvel</h2>

	<div class="contactsContainer">
	<?php foreach ($propertyImgs as $value) { ?>
		<div class="contactBox">
			<header>
				<a target="_blank" href="<?php echo PATH_ENTERPRISES.$value['img']; ?>"><img src="<?php echo PATH_ENTERPRISES.$value['img']; ?>" /></a>
			</header>
			<span style="margin: 4px calc(50% - 22.289px);"><a delete='ajax' id='<?php echo $value["id"]; ?>' href><i class='fa fa-times bgRed'></i></a></span>
			<div class="clear"></div>
		</div><!--contactBox-->
	<?php } ?>
	</div><!--contactsContainer-->

</div><!--contentBox-->
<const page='propertyImg' />

<?php } else if ($id and Database::exist('site_enterprises', $id)) {
	$enterprise = Database::fetch("SELECT * FROM `site_enterprises` WHERE `id` = $id");
?>

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Imóveis Cadastrados</h2>

	<div class="responsiveTable minWidth">
		<div class="row">
			<div class="col">
				<span>Nome</span>
			</div>
			<div class="col">
				<span>Preço (R$)</span>
			</div>
			<div class="col">
				<span>Área (m²)</span>
			</div>
			<div class="col">
				<span>Editar</span>
			</div>
			<div class="col">
				<span>Excluir</span>
			</div>
		</div><!--row-->
		<?php
		$properties = Database::selectAll('id, name, price, area', 'enterprise_properties', 'enterpriseID = '.$id);

		foreach ($properties as $value) { ?>
			<div class='row'>
			<div class='col'>
				<span><?php echo $value['name']; ?></span>
			</div>
			<div class='col'>
				<span><?php echo Convert::currencyBr($value['price']); ?></span>
			</div>
			<div class='col'>
				<span><?php echo $value['area']; ?></span>
			</div>
			<div class='col'>
				<span><a href='?property=<?php echo $value['id']; ?>'><i class='fa fa-pencil bgOrange'></i></a></span>
			</div>
			<div class='col'>
				<span><a delete='ajax' id='<?php echo $value["id"]; ?>' href><i class='fa fa-times bgRed'></i></a></span>
			</div>
			</div><!--row-->
		<?php } ?>
	</div><!--responsiveTable-->

</div><!--contentBox-->

<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Cadastrar Imóvel</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/cadastrar-imovel.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Preço</label>
			<input type="text" name="price" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Área (m²)</label>
			<input type="number" name="area" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Selecione as imagens</label>
			<input type="file" name="img[]" multiple />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="enterpriseID" value="<?php echo $id; ?>" />
			<input type="submit" name="submit" value="Cadastrar" />
		</div><!--formBox-->
	</form>

</div><!--contentBox-->

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Editar Empreendimento <?php echo $id; ?></h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/atualizar-empreendimento.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $enterprise['name']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Tipo</label>
			<select name="type">
				<option <?php if ($enterprise['type'] == 'residential') { echo 'selected'; } ?> value="residential">Residencial</option>
				<option <?php if ($enterprise['type'] == 'commercial') { echo 'selected'; } ?> value="commercial">Comercial</option>
			</select>
		</div><!--formBox-->
		<div class="formBox">
			<label>Preço</label>
			<input type="text" name="price" value="<?php echo $enterprise['price']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<?php if ($enterprise['img']) {
				echo "<a href='".PATH_ENTERPRISES.$enterprise['img']."' target='_blank'><img src='".PATH_ENTERPRISES.$enterprise['img']."' /></a>";
			} ?>
			<input type="file" name="img" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="id" value="<?php echo $enterprise['id']; ?>" />
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>

</div><!--contentBox-->
<const page='properties' />

<?php } else {

	$query = "";

	if (isset($_POST['submit']) and isset($_POST['search'])) {
		$search = $_POST['search'];
		$query = " WHERE `name` LIKE '%$search%' OR `price` LIKE '%$search%' ORDER BY `order`";

	}

	$pages = Panel::pagination('site_enterprises', 20);

	if ($query == "")
		$enterprises = Database::fetchData("SELECT * FROM `site_enterprises` ORDER BY `order`", ($pages['page'] - 1) * $pages['step'], $pages['step']);
	else
		$enterprises = Database::fetchAll("SELECT * FROM `site_enterprises` $query");
?>
<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Empreendimentos</h2>

	<form class="formPanel" method="post">
		<div class="formBox">
			<label><i class="fa fa-search"></i> Realizar uma busca</label>
			<input type="text" name="search" placeholder="<?php if ($query == "") { echo 'Procure por nome ou preço'; } else { echo count($enterprises).' resultado(s)'; } ?>" />
			<input type="submit" name="submit" value="Buscar" />
		</div><!--formBox-->
	</form>

	<div class="contactsContainer sortable">
	<?php foreach ($enterprises as $value) { ?>
		<div class="contactBox" id="item-<?php echo $value['id']; ?>" >
			<header>
				<?php if ($value['img']) { ?>
					<a target="_blank" href="<?php echo PATH_ENTERPRISES.$value['img']; ?>"><img src="<?php echo PATH_ENTERPRISES.$value['img']; ?>" /></a>
				<?php } else { ?>
					<h2><i class="fa fa-archive"></i></h2>
				<?php } ?>
			</header>
			<p><b>Nome: </b><?php echo $value['name'] ?></p>
			<p><b>Tipo: </b><?php if ($value['type'] == 'residential') { echo 'Residencial'; } else { echo 'Comercial'; } ?></p>
			<p><b>Preço: </b><?php echo 'R$'.Convert::currencyBr($value['price']); ?></p>

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
<const page='enterprises' />

<?php } ?>