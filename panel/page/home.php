<?php
	$onlineUsers = Database::getOnlineUsers();
	$totalVisits = Database::getTotalVisits();
	$todayVisits = Database::getTodayVisits();
?>

<div class="contentBox">
	<h2><i class="fa fa-home"></i> Painel de Controle - 
	<?php echo COMPANY_NAME ?></h2>
	<div class="metric bgOrange">
		<h2>Usuários Online</h2>
		<p><?php echo count($onlineUsers); ?></p>
	</div><!--metric-->
	<div class="metric bgRed">
		<h2>Visitas Hoje</h2>
		<p><?php echo $todayVisits; ?></p>
	</div><!--metric-->
	<div class="metric bgBlue">
		<h2>Total de Visitas</h2>
		<p><?php echo $totalVisits; ?></p>
	</div><!--metric-->
	<div class="clear"></div>
</div><!--contentBox-->

<div class="contentBox">

	<h2><i class="fa fa-rocket"></i> Usuários Online</h2>

	<div class="responsiveTable">
		<div class="row">
			<div class="col">
				<span>IP</span>
			</div>
			<div class="col">
				<span>Última Ação</span>
			</div>
		</div><!--row-->
		<?php
		foreach ($onlineUsers as $key => $value) {
			echo "<div class='row'>
			<div class='col'>
				<span>".$value['ip']."</span>
			</div>
			<div class='col'>
				<span>".date('d/m/Y H:i:s', strtotime($value['last_action']))."</span>
			</div>
			</div><!--row-->";
		}
		?>
	</div><!--responsiveTable-->

</div><!--contentBox-->