<!DOCTYPE html>
<html>
<head>
	<?php Site::printHead($infoSite["title"]); ?>
</head>
<body>

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
<section class="banner-container">
	<div style="background-image: url('<?php echo PATH; ?>img/bg/bg-form.jpg');" class="banner-single"></div><!--banner-single-->
	<div style="background-image: url('<?php echo PATH; ?>img/bg/bg-form2.jpg');" class="banner-single"></div><!--banner-single-->
	<div style="background-image: url('<?php echo PATH; ?>img/bg/bg-form3.jpg');" class="banner-single"></div><!--banner-single-->
	<div class="overlay"></div><!--overlay-->
		<div class="center">
		<form class="ajax-form" method="post">
			<h2>Qual o seu melhor e-mail?</h2>
			<input type="email" name="email" required />
			<input type="hidden" name="identificador" value="form_home" />
			<input type="submit" name="acao" value="Cadastrar!">
		</form>
		</div><!--center-->
		<div class="bullets"></div><!--bullets-->
</section><!--banner-container-->

<?php
	$query = "SELECT id, name, last_name, about FROM `site_staff` ORDER BY `order`";
	$staff = Database::fetchAll($query);
	foreach ($staff as $key => $value) {
		echo "<section class='descricao-autor'>
			<div class='center'>
			<div class='w100 left'>
				<h2 class='text-center'>";
					echo "<img src='".PATH."img/staff/".$value['id'].".jpg' />"." ".$value['name']." ".$value['last_name'];
				echo "</h2>
				<p>";
					echo $value['about'];
				echo "</p>
			</div><!--w50-->
			<div class='clear'></div>
			</div><!--center-->
		</section><!--descricao-autor-->";
	}
?>

<section class="especialidades">
	<div class="center">
		<h2 class="title">Especialidades</h2>

		<?php
			$query = "SELECT title, icon, about FROM `site_services` ORDER BY `order`";
			$services = Database::fetchAll($query);
			foreach ($services as $key => $value) {
				echo "<div class='w33 left box-especialidade'>
				<h3>
					<i class='".$value['icon']."' aria-hidden='true'></i>
				</h3>
				<h4>".$value['title']."</h4>
				<p>".$value['about']."</p>
				</div><!--box-especialidade-->";
			}
		?>

		<div class="clear"></div>
	</div><!--center-->
</section><!--especialidades-->

<section class="extras">
	<div class="center">
		<div id="depoimentos" class="w50 left depoimentos-container">
			<h2 class="title">Depoimentos dos nossos clientes</h2>

			<?php
				$query = "SELECT name, `date`, testimony FROM `site_depositions` ORDER BY `order` LIMIT 3";
				$depositions = Database::fetchAll($query);
				foreach ($depositions as $key => $value) {
			?>
					<div class="depoimento-single">
						<p class="depoimento-descricao">"
							<?php echo $value['testimony']; ?>
						"</p>
						<p class="nome-autor">
							<?php echo $value['name']; ?> - <?php echo date("d/m/Y", strtotime($value["date"])); ?>
						</p>
					</div><!--depoimento-single-->
			<?php } ?>
		</div><!--w50-->

		<div id="servicos" class="w50 left servicos-container">
			<h2 class="title">Serviços</h2>
			<div class="servicos">
			<ul>
				<?php
					$query = "SELECT about FROM `site_services_2` ORDER BY `order`";
					$services = Database::fetchAll($query);
					foreach ($services as $key => $value) {
				?>
						<li><?php echo $value['about']; ?></li>
					<?php } ?>
			</ul>
		</div><!--servicos-->
		</div><!--w50-->
		<div class="clear"></div>
	</div><!--center-->
</section><!--extras-->
</div><!--container-principal-->

<footer>
	<?php Site::printFooter(); ?>
</footer>

<?php Site::printScript(); ?>

</body>
</html>