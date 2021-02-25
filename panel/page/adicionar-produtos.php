<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Adicionar Produto</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/adicionar-produtos.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Descrição</label>
			<textarea name="about" required ></textarea>
		</div><!--formBox-->
		<div class="formBox">
			<label>Largura</label>
			<input type="number" name="width" min="0" max="900" step="5" value="0" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Altura</label>
			<input type="number" name="height" min="0" max="900" step="5" value="0" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Comprimento</label>
			<input type="number" name="length" min="0" max="900" step="5" value="0" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Peso</label>
			<input type="number" name="weight" min="0" max="900" step="1" value="0" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Quantidade</label>
			<input type="number" name="quantity" min="0" max="900" step="1" value="0" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Preço</label>
			<input type="text" name="price" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Selecione as imagens</label>
			<input type="file" name="img[]" multiple />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Adicionar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->