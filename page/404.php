<!DOCTYPE html>
<html>
<head>
	<?php Site::printHead("Not Found - ".$infoSite["title"]); ?>
</head>
<body>

<?php echo "<base base='".PATH."' />
<target target='$url[0]' />"; ?>

<div class="sucesso">
	<i class="fa fa-check"></i> Formulário enviado com sucesso!
</div>
<div class="overlay-loading">
	<img src="<?php echo PATH ?>img/gif/ajax-loader.gif" />
</div><!--overlay-loading-->

<header>
	<?php Site::printHeader(); ?>
</header>

<div class="container-principal">
<section class="erro-404">
	<div class="center">
	<div class="wraper-404">
		<h2>
			<i style="padding:0 10px;" class="fa fa-times"></i>
			A página Não Existe!
		</h2>
		<p>Deseja voltar para a 
			<a href="<?php echo PATH; ?>">página inicial</a>?
		</p>
	</div><!--wraper404-->
	</div><!--center-->
</section>
</div><!--container-principal-->

<footer>
	<?php Site::printFooter(); ?>
</footer>

<?php Site::printScript(); ?>

</body>
</html>