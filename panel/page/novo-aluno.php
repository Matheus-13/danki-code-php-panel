<div class="contentBox">
	<h2><i class="fa fa-pencil"></i> Cadastrar Aluno</h2>

	<form class="formPanel ajax" method="post" enctype="multipart/form-data" action="<?php echo PATH_PANEL ?>ajax/novo-aluno.php">
		<div class="formBox">
			<label>Nome</label>
			<input type="text" name="name" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Sobrenome</label>
			<input type="text" name="surname" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>E-mail</label>
			<input type="email" name="email" required />
		</div><!--formBox-->
		<div class="formBox">
			<label>Senha</label>
			<input type="password" name="password" required />
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