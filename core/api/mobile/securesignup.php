<?php
	print_r($_POST);
	print_r($_GET);

	file_put_contents($_SERVER['DOCUMENT_ROOT']."/securesignup.txt", ob_get_clean());
?>