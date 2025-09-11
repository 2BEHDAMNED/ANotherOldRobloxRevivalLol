<?php 

$name = $_GET['name'];
$id = intval($_GET['id']);

require_once $_SERVER['DOCUMENT_ROOT']."/core/asset.php";

$asset = Asset::FromID($id);

if($asset != null) {
    $new_name = preg_replace('/[^A-Za-z0-9]/', "", $asset->name);
    $new_name = strtolower($new_name);
    $new_name = str_replace(" ", "-", $new_name);
    
    if($new_name != $name) {
        die(header("Location: /$new_name-item?id=$id"));
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Page - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>