<?php

    require_once $_SERVER['DOCUMENT_ROOT']."/core/renderer.php";


    $image = TheFuckingRenderer::RenderPlayer(12);

    echo "<img src='data:image/png;base64,$image'>";
?>