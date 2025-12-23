<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/renderer.php";

	$base64 = TheFuckingRenderer::RenderModel(33, false);
?>
<img src="data:image/png;base64,<?= $base64 ?>">