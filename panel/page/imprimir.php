<?php
	require_once '../config.php';

	ob_start();

	if (isset($_GET['payments'])) {
		include('template/financial.php');

	} else {
		Site::redirect(PATH_PANEL);
	}

	$content = ob_get_contents();

	ob_end_clean();

	echo $content;
	echo "<script>
	window.onload = function() { print(); };
	</script>";
?>