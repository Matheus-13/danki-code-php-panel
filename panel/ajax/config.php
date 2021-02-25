<?php
	require_once '../config.php';
	if (!Login::logged()) die(json_encode(1));
?>