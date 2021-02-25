<!DOCTYPE html>
<html>
<head>
	<?php Panel::printHead(); ?>
</head>
<body>

<div class="loading"></div>

<section id="header">
	<div class="menuBtn">
		<i class="fa fa-bars"></i>
	</div>
	<div class="right logout">
		<a ajax href="<?php echo PATH_PANEL ?>agenda">
			<i class="far fa-calendar"></i> Agenda
		</a>
		<a ajax href="<?php echo PATH_PANEL ?>chat">
			<i class="far fa-comments"></i> Chat
		</a>
		<a href="<?php echo PATH_PANEL ?>logout">
			<i class="far fa-window-close"></i> Sair
		</a>
	</div><!--logout-->
	<div class="clear"></div>
</section><!--header-->

<section id="menu">
	<a href="<?php echo PATH_PANEL; ?>">
		<img src='<?php echo PATH; ?>img/site/logo.png' />
	</a>
	<div class="user">
		<p><?php echo $_SESSION['name'] ?></p>
	</div>
	<div class="menuItems">
		<h2>Cadastro</h2>
		<a href="<?php echo PATH_PANEL ?>adicionar-depoimento">Adicionar Depoimento</a>
		<a href="<?php echo PATH_PANEL ?>adicionar-servico">Adicionar Serviço</a>
		<h2>Gestão</h2>
		<a href="<?php echo PATH_PANEL ?>listar-depoimentos">Listar Depoimentos</a>
		<a href="<?php echo PATH_PANEL ?>listar-servicos">Listar Serviços</a>
		<a href="<?php echo PATH_PANEL ?>gerenciar-slide">Gerenciar Slide</a>
		<h2>Administração do Painel</h2>
		<a href="<?php echo PATH_PANEL ?>editar-usuario">Editar Usuário</a>
		<a href="<?php echo PATH_PANEL ?>cadastrar-usuario">Cadastrar Usuário</a>
		<h2>Configuração Geral</h2>
		<a href="<?php echo PATH_PANEL ?>editar-site">Editar</a>
		<h2>Gestão de Notícias</h2>
		<a href="<?php echo PATH_PANEL ?>adicionar-noticia">Adicionar Notícia</a>
		<a href="<?php echo PATH_PANEL ?>gerenciar-noticias">Gerenciar Notícias</a>
		<a href="<?php echo PATH_PANEL ?>gerenciar-categorias">Gerenciar Categorias</a>
		<h2>Gestão de Clientes</h2>
		<a href="<?php echo PATH_PANEL ?>cadastrar-clientes">Cadastrar Clientes</a>
		<a href="<?php echo PATH_PANEL ?>gerenciar-clientes">Gerenciar Clientes</a>
		<h2>Controle Financeiro</h2>
		<a href="<?php echo PATH_PANEL ?>visualizar-pagamentos">Visualizar Pagamentos</a>
		<h2>Controle de Estoque</h2>
		<a href="<?php echo PATH_PANEL ?>adicionar-produtos">Adicionar Produtos</a>
		<a href="<?php echo PATH_PANEL ?>listar-produtos">Listar Produtos</a>
		<h2>Gestão de Imóveis</h2>
		<a href="<?php echo PATH_PANEL ?>cadastrar-empreendimento">Cadastrar Empreendimento</a>
		<a href="<?php echo PATH_PANEL ?>listar-empreendimentos">Listar Empreendimentos</a>
		<h2>Gestão EAD</h2>
		<a href="<?php echo PATH_PANEL ?>novo-aluno">Novo Aluno</a>
		<a href="<?php echo PATH_PANEL ?>novo-curso">Novo Curso</a>
		<a href="<?php echo PATH_PANEL ?>novo-modulo">Novo Módulo</a>
		<a href="<?php echo PATH_PANEL ?>nova-aula">Nova Aula</a>
	</div><!--menuItems-->
</section><!--menu-->

<section id="main">
	<div class="loadingBox"></div>
	<section id="content">
		<?php Panel::loadPage(); ?>
	</section><!--content-->
</section><!--main-->

<?php Panel::printScript(); ?>

</body>
</html>