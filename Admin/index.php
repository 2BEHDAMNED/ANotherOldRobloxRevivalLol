<?php
	// reject user if not admin...
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>ANORRL | Administration</title>
		<link id="ctl00_Imports" rel="stylesheet" type="text/css" href="/css/AllCSS.css?t=<?= time() ?>">
		<link id="ctl00_Favicon" rel="Shortcut Icon" type="image/ico" href="/favicon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="/js/jquery.js" type="text/javascript"></script>
		<script src="/js/WebResource.js" type="text/javascript"></script>
		<style>
			body {
				height:100vh;
				margin: 0;
				padding: 0;
				font-family: Verdana, Geneva, Tahoma, sans-serif;
				font-size:12px;
			}
			#AdminSidebar {
				float:left;
				width:204px;
				border-right:black 1px solid;
				height:100%;
				background:#eaeaea;
				text-align: center;
				padding:0px;
			}
		</style>
		<style>
			ul, #myUL {
				list-style-type: none;
			}

			#myUL {
				margin: 0;
				padding: 0;
			}

			.caret {
				cursor: pointer;
				-webkit-user-select: none; /* Safari 3.1+ */
				-moz-user-select: none; /* Firefox 2+ */
				-ms-user-select: none; /* IE 10+ */
				user-select: none;
			}

			.caret::before {
				content: "\25B6";
				color: black;
				display: inline-block;
				margin-right: 6px;
			}

			.caret-down::before {
				-ms-transform: rotate(90deg); /* IE 9 */
				-webkit-transform: rotate(90deg); /* Safari */'
				transform: rotate(90deg);  
			}

			.nested {
				display: none;
			}

			.active {
				display: block;
			}
		</style>
	</head>
	<body>
		<div id="AdminSidebar">
			<img src="/images/menus/IOTA128x.png" width="140" style="margin-left:10px;">
			<div style="text-align: left;padding:10px;">
				<h4 style="margin: 0">You are: <?= "Admin" ?></h4><br>
				<hr>
				<span><b id="unverifiedlabel">0</b> unverified assets</span><br>
				<span><b>0</b> players in <b>0</b> games</span><br>
			</div>
			<div style="text-align: left;padding:1px; border: 1px dotted black;margin: 0 10px;">
				<ul id="myUL">
					<li><span class="caret caret-down">Administration</span>
						<ul class="nested active">
							<li><a href="javascript:LoadFrame('approve')">Approve</a></li>
							<li><a href="javascript:LoadFrame('user')">Users</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<iframe id="panel" src="/Admin/components/user" style="position:fixed; width:89%; height: 100%; border: 0;"></iframe>

		<script>
			var toggler = document.getElementsByClassName("caret");
			var i;

			for (i = 0; i < toggler.length; i++) {
				toggler[i].addEventListener("click", function() {
					this.parentElement.querySelector(".nested").classList.toggle("active");
					this.classList.toggle("caret-down");
				});
			}

			function LoadFrame(page) {
				$("#panel").attr("src","/Admin/components/"+page)
			}

			/*window.setInterval(function() {
				$.post( "postadmin", { type: "getunverified" }).done(function( data ) {
					$("#unverifiedlabel").html(data);
				});
			}, 2000);*/
		</script>
	</body>
</html>