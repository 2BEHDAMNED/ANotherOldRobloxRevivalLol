<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /"));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>People - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css?t=<?= time() ?>">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js?t=<?= time() ?>"></script>
		<script src="/js/people.js?t=1760879572"></script>
		<style>

			#Users #SearchBox {
				width: 400px
			}

			#Users #Submit {
				width: 70px
			}

			#Users form {
				margin: 5px auto;
				margin-bottom: 15px;
				text-align: center; 
			}

			#Users table {
				border: 2px solid black;
				background: #222;
				*padding: 5px;
				border-collapse: separate;
			}

			#Users tr {
				border-bottom: 1px solid black;
			}

			#Users th {
				background: #111;
			}

			h2 {
				margin: 0;
				margin-top: 10px;
			}

			#Users {
				border: 2px solid black;
				background: #2a2a2a;
				padding: 10px;
			}

			#UsersNavLinks {
				text-align: center;
				margin: 15px;
				margin-bottom: 5px;
				display: none;
			}

			#FormPanel {
				*display: none;
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2>People</h2>
					<div id="Users">
						<div method="GET" id="FormPanel">
							<input id="SearchBox" name="query" type="text" placeholder="Look for users lol">
							<input id="Submit" type="submit" value="Search" onclick="ANORRL.People.Submit(); return false;">
						</div>
						<table id="UsersDataTable">
							<tr>
								<th width="80" style="border:0">Avatar</th>
								<th width="200" style="border:0">Name</th>
								<th style="border:0; width: 600px; max-width: 600px;">Blurb</th>
								<th width="150" style="border:0">Active</th>
							</tr>

							
						</table>
						<div id="UsersNavLinks">
							<a id="BackPager" href="javascript:ANORRL.People.DeadvanceFeed()">&lt;&lt; Back</a> <input maxlength="4" id="NumberPutter"> of <span id="Counter"></span> <a id="NextPager" href="javascript:ANORRL.People.AdvanceFeed()">Next &gt;&gt;</a>
						</div>
					</div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>