<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Cadastrar Empreendimento</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/cadastrar-empreendimento.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Tipo</label>
			<select name="type">
				<option value="residential">Residencial</option>
				<option value="commercial">Comercial</option>
			</select>
		</div><!--formBox-->
		<div class="formBox">
			<label>Preço</label>
			<input type="text" name="price" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Imagem</label>
			<input type="file" name="img" />
		</div><!--formBox-->
		<div class="formBox">
			<input type="submit" name="submit" value="Cadastrar" />
		</div><!--formBox-->
	</form>
</div><!--contentBox-->