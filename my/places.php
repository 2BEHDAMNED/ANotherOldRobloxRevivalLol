<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /"));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your Stuff - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<script src="/js/stuff.js"></script>
		<style>
			#StuffContainer {
				width: 876px;
				margin: 0 auto;
				margin-bottom: 15px;
			}

			#StuffContainer h1 {
				margin-bottom: 0px;
				margin-top: 5px;
				width:828px;
			}

			#StuffContainer #StuffNavigation {
				border: 2px solid black;
				background: #222;
				width:184px;
				display: inline-block;
				vertical-align: top;
				border-top: 0;
				
			}

			#StuffNavigation ul {
				list-style: none;
				padding: 15px;
				margin: 0;
			}

			#StuffNavigation hr {
				border: 0;
			}

			#StuffNavigation ul > li[selected] {
				font-weight: bold;
			}

			#AssetsContainer {
				display: inline-block;
				border: 2px solid black;
				border-left: none;
				min-height: 180px;
				width:826px;
				background: #222;
				vertical-align: middle;

				padding: 5px 20px;

				white-space: nowrap;
				border-top: 0;
				position: relative;
			}

			#AssetsContainer table {
				min-height: 180px;
				padding: 5px;
			}

			#AssetsContainer #Paginator {
				display: block;
				background: #111111;
				padding: 10px;
				text-align: center;
			}

			#AssetsContainer #Paginator input {
				font-size: 12px;
				width: 30px;
				height: 13px;
				text-align: center;
			}

			#StuffNavigation #CreateArea {
				display: block;
				width: 100%;
				padding: 5px 0px;
				background: #111;
				font-weight: bold;
				text-align: center;
			}
			
			#StuffNavigation #CreateArea a {
				display: inline-block;
				width: 45%;
			}

			#StuffNavigation #CreateArea a[disabled] {
				color: gray;
			}
			#AssetsContainer table[hidden] {
				display: none;
			}

			#AssetsContainer #Loading, 
			#AssetsContainer #NoAssets {
				font-size: 18px;
				width: 100%;
				display: block;
				text-align: center;
				line-height: 180px;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="StuffContainer">
						<h1>Your Places</h1>
						<div id="AssetsContainer">
							<div id="StatusText">
								<b id="Loading" style="display: none">Loading your places...</b>
								<b id="NoAssets" style="display: none">You have no places!</b>
							</div>
						
							<table hidden></table>

							<div id="Paginator" style="display: none">
								<a href="" id="PrevPager">&lt;&lt;Previous</a> Page <input maxlength="4"> of <span id="Pages">1</span> <a href="" id="NextPager">Next&gt;&gt;</a>
							</div>
						</div>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>