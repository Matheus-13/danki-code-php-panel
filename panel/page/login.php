<!DOCTYPE html>
<html>
<head>
	<?php Panel::printHead(); ?>
</head>
<body>

<section id="login">
	<div class="box">
		<a href="<?php echo PATH; ?>">
			<img src='<?php echo PATH; ?>img/site/logo.png' />
		</a>
		<?php
			if (isset($_POST['submit'])) {
				$remember = isset($_POST['remember']) ? true : false;
				$enter = Login::enter($_POST['email'], $_POST['password'], $remember);
				if (!$enter[0])
					echo "<div class='error'>$enter[1]</div>";
			}
		?>
		<form method="post">
			<input type="email" name="email" placeholder="Email" required />
			<input type="password" name="password" placeholder="Senha" required />
			<input class="left" type="submit" name="submit" value="Entrar" />
			<div class="right">
				<label>Ficar conectado </label>
				<input type="checkbox" name="remember" />
			</div>
			<div class="clear"></div>
		</form>
	</div><!--box-->
</section>

</body>
</html>