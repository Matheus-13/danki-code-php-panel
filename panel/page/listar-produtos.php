<?php
$id = isset($_GET['edit']) ? (int)$_GET['edit'] : false;
if (!Database::rowCount("SELECT `id` FROM `site_stock` WHERE `id` = ?", array($id)))
	$id = false;

if ($id) {
	$product = Database::fetch("SELECT * FROM `site_stock` WHERE `id` = $id");
?>

<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Editar Produto <?php echo $id; ?></h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/atualizar-produto.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $product['name']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Descrição</label>
			<textarea name="about"><?php echo $product['about']; ?></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Largura</label>
			<input type="number" name="width" min="0" max="900" step="5" value="<?php echo $product['width']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Altura</label>
			<input type="number" name="height" min="0" max="900" step="5" value="<?php echo $product['height']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Comprimento</label>
			<input type="number" name="length" min="0" max="900" step="5" value="<?php echo $product['length']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Peso</label>
			<input type="number" name="weight" min="0" max="900" step="1" value="<?php echo $product['weight']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Quantidade</label>
			<input type="number" name="quantity" min="0" max="900" step="1" value="<?php echo $product['quantity']; ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Preço</label>
			<input type="text" name="price" value="<?php echo Convert::currencyBr($product['price']); ?>" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Adicionar imagens</label>
			<input type="file" name="img[]" multiple />
		</div><!--formBox-->
		<div class="formBox">
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<input type="submit" name="submit" value="Atualizar" />
		</div><!--formBox-->
	</form>

	<?php $productImgs = Database::fetchAll("SELECT `id`, `img` FROM `stock_images` WHERE `product` = $id"); ?>

	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Imagens do Produto</h2>

	<div class="contactsContainer">
	<?php foreach ($productImgs as $value) { ?>
		<div class="contactBox">
			<header>
				<a target="_blank" href="<?php echo PATH_STOCK.$value['img']; ?>"><img src="<?php echo PATH_STOCK.$value['img']; ?>" /></a>
			</header>
			<span style="margin: 4px calc(50% - 22.289px);"><a delete='ajax' id='<?php echo $value["id"]; ?>' href><i class='fa fa-times bgRed'></i></a></span>
			<div class="clear"></div>
		</div><!--contactBox-->
	<?php } ?>
	</div><!--contactsContainer-->

</div><!--contentBox-->
<const page='productImgs' />

<?php } else {

	$query = "";

	if (isset($_POST['submit']) and isset($_POST['search'])) {
		$search = $_POST['search'];
		$query = " WHERE `name` LIKE '%$search%' OR `about` LIKE '%$search%'";

	} else if (isset($_GET['out'])) {
		$query = " WHERE `quantity` = 0";
	}

	$pages = Panel::pagination('site_stock', 20);

	if ($query == "")
		$products = Database::fetchData("SELECT * FROM `site_stock`", ($pages['page'] - 1) * $pages['step'], $pages['step']);
	else
		$products = Database::fetchAll("SELECT * FROM `site_stock` $query");

	if (!isset($_GET['out']) and Database::rowCount("SELECT `id` FROM `site_stock` WHERE `quantity` = 0 LIMIT 1")) {
		Panel::alert('warning', 'Você está com produtos em falta. Clique <a href="?out">aqui</a> para visualizá-los.');
	}
?>
<div class="contentBox">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Lista de Produtos</h2>

	<form class="formPanel" method="post">
		<div class="formBox">
			<label><i class="fa fa-search"></i> Realizar uma busca</label>
			<input type="text" name="search" placeholder="<?php if ($query == "") { echo 'Pesquisar'; } else { echo count($products).' resultado(s)'; } ?>" />
			<input type="submit" name="submit" value="Buscar" />
		</div><!--formBox-->
	</form>

	<div class="contactsContainer">
	<?php foreach ($products as $value) {
		$img = Database::fetch("SELECT `img` FROM `stock_images` WHERE product = $value[id] LIMIT 1"); ?>
		<div class="contactBox">
			<header>
				<?php if ($img) { ?>
					<a target="_blank" href="<?php echo PATH_STOCK.$img['img']; ?>"><img src="<?php echo PATH_STOCK.$img['img']; ?>" /></a>
				<?php } else { ?>
					<h2><i class="fa fa-archive"></i></h2>
				<?php } ?>
			</header>
			<p><b>Nome: </b><?php echo $value['name'] ?></p>
			<p><b>Descrição: </b><?php echo $value['about'] ?></p>
			<p><b>Largura: </b><?php echo $value['width'] ?></p>
			<p><b>Altura: </b><?php echo $value['height'] ?></p>
			<p><b>Comprimento: </b><?php echo $value['length'] ?></p>
			<p><b>Peso: </b><?php echo $value['weight'] ?></p>
			<p><b>Preço: </b>R$<?php echo Convert::currencyBr($value['price']); ?></p>

			<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="ajax/atualizar-quantidade.php">
				<p><b>Quantidade: </b></p>
				<input type="number" name="quantity" min="0" max="900" step="1" value="<?php echo $value['quantity'] ?>" required />
				<input type="hidden" name="id" value="<?php echo $value['id']; ?>" />
				<input type="submit" name="submit" value="Atualizar" />
			</form>

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

<const page='products' />
<?php } ?>