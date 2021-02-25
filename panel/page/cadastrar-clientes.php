<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Cadastrar Clientes</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/register-customers.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" />
		</div><!--formBox-->
		<div class="formBox">
			<label>E-mail</label>
			<input type="email" name="email" />
		</div><!--formBox-->
		<div class="formBox">
			<label>Tipo</label>
			<select name="type">
				<option value="physical">Físico</option>
				<option value="legal">Jurídico</option>
			</select>
		</div><!--formBox-->
		<div class="formBox" ref="cpf">
			<label>CPF</label>
			<input type="text" name="cpf" />
		</div><!--formBox-->
		<div class="formBox" style="display: none;" ref="cnpj">
			<label>CNPJ</label>
			<input type="text" name="cnpj" />
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