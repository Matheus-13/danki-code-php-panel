<head>
	<?php Site::printHead($infoSite["title"]); ?>
</head>

<header>
	<?php Site::printHeader(); ?>
</header>

<?php
if (!isset($url[2])) {
	$categoria = Database::connect()->prepare("SELECT * FROM `site_categories` WHERE slug = ?");
	$categoria->execute(array(@$url[1]));
	$categoria = $categoria->fetch();
?>

<section class="header-noticias">
	<div class="center">
		<h2><i class="fa fa-bell-o" aria-hidden="true"></i></h2>
		<h2 style="font-size: 21px;">Acompanhe as últimas <b>notícias do portal</b></h2>
	</div><!--center-->
</section>

<section class="container-portal">
	<div class="center">
		
			<div class="sidebar">
				<div class="box-content-sidebar">
					<h3><i class="fa fa-search"></i> Realizar uma busca:</h3>
					<form method="post">
						<input type="text" name="parametro" placeholder="O que deseja procurar?" required>
						<input type="submit" name="buscar" value="Pesquisar!">
					</form>
				</div><!--box-content-sidebar-->
				<div class="box-content-sidebar">
					<h3><i class="fa fa-list-ul" aria-hidden="true"></i> Selecione a categoria:</h3>
					<form>
						<select name="categoria">
						<option value="" selected="">Todas as categorias</option>
							<?php
								$categorias = Database::connect()->prepare("SELECT * FROM `site_categories` ORDER BY `order` ASC");
								$categorias->execute();
								$categorias = $categorias->fetchAll();
								foreach ($categorias as $key => $value) {
							?>
								<option <?php if($value['slug'] == @$url[1]) echo 'selected'; ?> value="<?php echo $value['slug']; ?>"><?php echo $value['name']; ?></option>
							<?php } ?>
							
						</select>
					</form>
				</div><!--box-content-sidebar-->
				<div class="box-content-sidebar">
					<h3><i class="fa fa-user" aria-hidden="true"></i> Sobre o autor:</h3>
						<div class="autor-box-portal">
							<div class="box-img-autor"></div>
							<div class="texto-autor-portal text-center">
								<?php
									$infoSite = Database::connect()->prepare("SELECT `name`, `last_name`, `about`, `avatar` FROM `site_staff` WHERE `id` = ?");
									$infoSite->execute(array('12345'));
									$infoSite = $infoSite->fetch();
								 ?>
								<h3><?php echo $infoSite['name'].' '.$infoSite['last_name'] ?></h3>
								<p><?php echo substr($infoSite['about'],0,300).'...' ?></p>
							</div><!--texto-autor-portal-->
						</div><!--autor-box-portal-->
				</div><!--box-content-sidebar-->
			</div><!--sidebar-->

			<div class="conteudo-portal">
					<div class="header-conteudo-portal">
						<?php
							$porPagina = 10;
							if(!isset($_POST['parametro'])){
								if($categoria['name'] == ''){
									echo '<h2>Visualizando todos os Posts</h2>';
								}else{
									echo '<h2>Visualizando Posts em <span>'.$categoria['name'].'</span></h2>';
								}
							}else{
								echo '<h2><i class="fa fa-check"></i> Busca realizada com sucesso!</h2>';
							}

							$query = "SELECT * FROM `site_news`";
							if($categoria['name'] != ''){
								$categoria['id'] = (int)$categoria['id'];
								$query .= " WHERE `category` = $categoria[id]";
							}
							if(isset($_POST['parametro'])){
								if(strstr($query,'WHERE') !== false){
									$busca = $_POST['parametro'];
									$query .= " AND `title` LIKE '%$busca%'";
								}else{
									$busca = $_POST['parametro'];
									$query .= " WHERE `title` LIKE '%$busca%'";
								}
							}
							$query2 = "SELECT * FROM `site_news`"; 
							if($categoria['name'] != ''){
									$categoria['id'] = (int)$categoria['id'];
									$query2 .= " WHERE `category` = $categoria[id]";
							}
							if(isset($_POST['parametro'])){
								if(strstr($query2,'WHERE') !== false){
									$busca = $_POST['parametro'];
									$query2 .= " AND `title` LIKE '%$busca%'";
								}else{
									$busca = $_POST['parametro'];
									$query2 .= " WHERE `title` LIKE '%$busca%'";
								}
							}
							$totalPaginas = Database::connect()->prepare($query2);
							$totalPaginas->execute();
							$totalPaginas = ceil($totalPaginas->rowCount() / $porPagina);
							if(!isset($_POST['parametro'])){
								if(isset($_GET['pagina'])){
									$pagina = (int)$_GET['pagina'];
									if($pagina > $totalPaginas){
										$pagina = 1;
									}
									
									$queryPg = ($pagina - 1) * $porPagina;
									$query .= " ORDER BY `date` LIMIT $queryPg, $porPagina";
								}else{
									$pagina = 1;
									$query .= " ORDER BY `date` LIMIT 0, $porPagina";
								}
							}else{
								$query .= " ORDER BY `date`";
							}
							$sql = Database::connect()->prepare($query);
							$sql->execute();
							$noticias = $sql->fetchAll();
						?>
						
						
					</div>
					<?php
						foreach($noticias as $key => $value){
						$sql = Database::connect()->prepare("SELECT `slug` FROM `site_categories` WHERE `id` = ?");
						$sql->execute(array($value['category']));
						$categoriaNome = $sql->fetch()['slug'];
					?>
					<div class="box-single-conteudo">
						<h2><?php echo date('d/m/Y',strtotime($value['date'])) ?> - <?php echo $value['title']; ?></h2>
						<p><?php echo substr(strip_tags($value['content']),0,400).'...'; ?></p>
						<a href="<?php echo PATH; ?>noticias/<?php echo $categoriaNome; ?>/<?php echo $value['slug']; ?>">Leia mais</a>
					</div><!--box-single-conteudo-->
					<?php } ?>

					

					<div class="paginator">
						<?php
							if(!isset($_POST['parametro'])){
							for($i = 1; $i <= $totalPaginas; $i++){
								$catStr = ($categoria['name'] != '') ? '/'.$categoria['slug'] : '';
								if($pagina == $i)
									echo '<a class="active-page" href="'.PATH.'noticias'.$catStr.'?pagina='.$i.'">'.$i.'</a>';
								else
									echo '<a href="'.PATH.'noticias'.$catStr.'?pagina='.$i.'">'.$i.'</a>';
							}
							}
						?>
						
					</div><!--paginator-->
			</div><!--conteudo-portal-->


			<div class="clear"></div>
	</div><!--center-->

</section><!--container-portal-->

<?php } else { ?>

<?php
	$url = explode('/',$_GET['url']);
	

	$verifica_categoria = Database::connect()->prepare("SELECT * FROM `site_categories` WHERE `slug` = ?");
	$verifica_categoria->execute(array($url[1]));
	if($verifica_categoria->rowCount() == 0){
		Site::redirect(PATH.'noticias');
	}
	$categoria_info = $verifica_categoria->fetch();

	$post = Database::connect()->prepare("SELECT * FROM `site_news` WHERE slug = ? AND category = ?");
	$post->execute(array($url[2],$categoria_info['id']));
	if($post->rowCount() == 0){
		Site::redirect(PATH.'noticias');
	}

	//É POR QUE MINHA NOTICIA EXISTE
	$post = $post->fetch();

?>
<section class="noticia-single">
	<div class="center">
	<header>
		<h1><i class="fa fa-calendar"></i> <?php echo $post['date'] ?> - <?php echo $post['title'] ?></h1>
	</header>
	<article>
		<?php echo $post['content']; ?>
	</article>

	<?php if (Login::logged()) {
		if (isset($_POST['submit']) and isset($_POST['comment']) and isset($_POST['news_id'])) {
			$news_id = (int)$_POST['news_id'];
			if (Database::exist('site_news', $news_id)) {
				Database::insert('site_comments', '?,?,?,?', array(null, $news_id, $_SESSION['id'], $_POST['comment']));
			}
		}
	?>
		<h2 class="postar-comentario">Faça um comentário <i class="fa fa-comment"></i></h2>
		<form method="post">
			<input type="text" value="<?php echo $_SESSION['name']; ?>" disabled />
			<textarea name="comment" placeholder="Seu comentário..." required></textarea>
			<input type="hidden" name="news_id" value="<?php echo $post['id']; ?>">
			<input type="submit" name="submit" value="Comentar" />
		</form>
	<?php }

	$admins = Database::selectArray("id, Concat(name, ' ', last_name)", "panel_admins");
	$comments = Database::selectAll('id, adminID, comment', 'site_comments', 'news_id = '.$post['id']);
	?>

	<h2 class="postar-comentario"><?php echo count($comments); ?> Comentários <i class="fa fa-comment"></i></h2>

	<?php foreach ($comments as $value) { ?>
	<div class="box-coment-noticia">
		<h3><?php echo $admins[$value['adminID']][0]; ?></h3>
		<p><?php echo $value['comment']; ?></p>
		<?php /* <form method="post">
			<textarea name="comment" placeholder="Sua resposta..." required></textarea>
			<input type="hidden" name="news_id" value="<?php echo $post['id']; ?>">
			<input type="hidden" name="comment_id" value="<?php echo $value['id']; ?>">
			<input type="submit" name="reply" value="Comentar" />
		</form> */ ?>
	</div>
	<?php } ?>

	</div>
</section>

<?php } ?>

<footer>
	<?php Site::printFooter(); ?>
</footer>

<?php Site::printScript(); ?>