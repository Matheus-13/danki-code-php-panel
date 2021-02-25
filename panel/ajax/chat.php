<?php
	require_once 'config.php';
	if (!isset($_POST['action'])) die(json_encode(2));

	if ($_POST['action'] == 'get') {
		if (!isset($_SESSION['lastMessageId']))
			$_SESSION['lastMessageId'] = 0;

		$messages = Database::selectAll('*', 'panel_chat', 'id > '.$_SESSION['lastMessageId']);
		if (empty($messages)) die();

		$admins = Database::selectArray("id, Concat(name, ' ', last_name)", "panel_admins");
		foreach ($messages as $value) {
			$_SESSION['lastMessageId'] = $value['id'];
			echo "<div class='message'>
			<label>".$admins[$value['adminID']][0]."</label>
			<p>".$value['message']."</p></div>";
		}

	} else if ($_POST['action'] == 'insert' and isset($_POST['message'])) {
		if (Database::insert('panel_chat', '?,?,?', array(null, $_SESSION['id'], $_POST['message'])))
			$data['success'] = true;
		else
			$data['success'] = false;
		die(json_encode($data));

	} else if ($_POST['action'] == 'reset') {
		if (Database::connect()->exec("DELETE FROM `panel_chat`"))
			$data['success'] = true;
		else
			$data['success'] = false;
		die(json_encode($data));
	}
?>