<?php 

$name = $_GET['name'];
$id = intval($_GET['id']);

require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";

$asset = Asset::FromID($id);

if($asset != null) {
    $new_name = preg_replace('/^[\w]*$/', "", $asset->name);
    $new_name = strtolower($new_name);
    $new_name = str_replace(" ", "-", $new_name);
    
    if($new_name != $name) {
        die(header("Location: /$new_name-item?id=$id"));
    }
}

//


?>