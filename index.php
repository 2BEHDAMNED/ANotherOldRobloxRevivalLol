<!DOCTYPE html>
<html>
	<head>
		<title>Welcome to ANORRL!</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css">
		<script src="/js/jquery.js"></script>
		<script src="/js/main.js"></script>
		<style>
			#IntroductionBox {
				width: 500px;
			}

			#IntroductionBox h2 {
				margin-bottom: 8px;
			}

			#IntroductionBox h3 {
				margin-bottom: 0px;
				margin-top: 0px;
				margin-left: 10px;
				font-size: 16px;
			}

			#IntroductionBox p {
				margin-left: 15px;
				margin-top: 5px;
				margin-bottom: 10px;
			}

			#ShowcaseContainer {
				margin-top: -195px;
				float: right;
			}

			#ShowcaseContainer h3 {
				margin: 0;
				
			}

			h2, h3 {
				font-style:italic;
			}

			#ShowcaseBox {
				border: 3px solid black;
				background: #282828;
				width: 355px;
				padding: 10px;
			}

			#ShowcaseBox #Showcase {
				width: 327px;
				height: 184px;
				background: #1a1919;
				border: 2px solid #090909;
				margin: 0 auto; 
				display:block;
			}

			#ShowcaseBox #Carousel {
				border: 2px solid #090909;
				border-top: none;
				margin: 0 auto;
				width:fit-content;
				width:initial\9;
				height:42px;
				text-align: center;
				background: #171717;
				
			}

			#ShowcaseBox #Carousel img {
				display:inline;
				width:42px;
				height:42px;
			}

			#ShowcaseBox #Carousel img:hover,
			#ShowcaseBox #Carousel img.selected {
				background: #444;
				cursor: pointer;
			}

			#NewUsersContainer {
				margin-top: -35px;
			}

			#NewUsersContainer h3 {
				margin-bottom: 0px;
				margin-left: 15px;
				width: 110px;
			}

			#NewUsersContainer #NewUsersBox {
				border: 2px solid #090909;
				margin: 0 auto;
				background: #171717;
				padding: 10px;
				padding-left: 15px;
				width: 100%;
				table-layout: fixed;
			}



			#NewUsersContainer #NewUsersBox .User {
				border: 2px solid #090909;
				background: #282828;
				width: 104px;
				display:inline-block;
				padding: 5px;
				text-align: center;
				margin-right: 5px;
				vertical-align: middle;
			}

			#NewUsersContainer #NewUsersBox .User img {
				width: 100px;
				border: 2px solid #090909;
				background: #1a1919;
			}

			#NewUsersContainer #NewUsersBox .User span {
				width: 100px;
				display: block;
				font-family: punk;
				font-weight: bold;
				word-break: break-all;
				margin: 0 auto;
				height: 28px;
			}

			#PopularGamesContainer {
				
			}

			#PopularGamesContainer h3 {
				margin-bottom: 0px;
				margin-left: 15px;
				width: 150px;
			}

			#PopularGamesContainer	#PopularGamesBox {
				border: 2px solid #090909;
				margin: 0 auto;
				background: #171717;
				padding: 10px;
				padding-left: 15px;
				width: 100%;
			}

			.PopularGame img {
				width: 300px;
				height: 169px;
				border: 2px solid #090909;
			}

			.PopularGame #Play {
				width: 212px;
				height: 54px;
				background: url(/images/btn_play_54h.png);
				display: inline-block;
			}

			.PopularGame #Play:hover {
				background-position: 0 54px;
			}

			.PopularGame #ShowcaseBigImages {
				display: table-cell;
				vertical-align: top;
				width:304px;
				text-align:center;
			}

			.PopularGame #ShowcaseBigImages img {
				background:	#282828
			}

			.PopularGame #ShowcaseDetails {
				display: table-cell;
				vertical-align: top;

				width: 282px;
				height: 225px;
			}

			.PopularGame #ShowcaseDetails #NameAndCreator {
				margin-left: 0px;
				margin-top: 0px;
				font-size:15px;
				width: 270px;
				background: black;
				font-family: punk;
				font-weight: bold;
				padding: 5px;
				padding-left: 15px;
				padding-top: 10px;
			}

			.PopularGame #ShowcaseDetails #NameAndCreator a {
				display: block;
				color: white;
			}

			.PopularGame #ShowcaseDetails #NameAndCreator #Creator {
				font-size: 11px;
			}

			.PopularGame #ShowcaseDetails code {
				border: 2px solid black;
				padding: 10px;
				background: #282828;
				height: 175px;
				display: block;
				width: 266px;
			}

			#PopularGames {
				text-align: center;margin-left: 10px;height: 195px;overflow: hidden;
				background: #0f0f0f;
				
			}

			#PopularGames img {
				width: 227px;height: 128px;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="IntroductionBox">
						<h2 style="width: -moz-available;">What is ANORRL?</h2>
						<p><b>ANORRL</b> is a 2012 "Old Roblox Revival" which aims to bring a fresh new look at the past!</p>
						<p><b>ANORRL</b> stands for <code>"<b>AN</b>other <b>O</b>ld <b>R</b>oblox <b>R</b>evival <b>L</b>ol"</code></p>
						<div style="margin-left: 5px;">
							<h3 style="width: -moz-available;">So what is a "Old Roblox Revival"</h3>
							<p>
								It is a project that aims to recreate and bring life into relatively old clients that ROBLOX has created in the past.<br>
								<b>Please note that</b>: These revivals do tend to be private (along with this one) as to ensure the maximum security because not only are These
								clients old, they are also insecure!
							</p>
						</div>
						
					</div>
					<div id="ShowcaseContainer">
						<h3>Showcase</h3>
						<div id="ShowcaseBox">
						<img id="Showcase" src="/images/avatar.png">
							<div id="Carousel">
								<img class="selected" src="/images/avatar.png">
								<img src="/images/avatar.png">
								<img src="/images/avatar.png">
								<img src="/images/avatar.png">
								<img src="/images/avatar.png">
							</div>
						</div>
						
					</div>
					<br style="clear:both">

					<div id="NewUsersContainer">
						<h3>New Users!</h3>
						<table id="NewUsersBox">
							<tr>
								<td>
									<div class="User">
										<a href="">
											<img src="/images/avatar.png">
											<span>WWWWWWWWWWWWWWWWWWWW</span>
										</a>
									</div>
								</td>
								<td>
									<div class="User">
										<a href="">
											<img src="/images/avatar.png">
											<span>Username</span>
										</a>
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</table>
					</div>


					<div id="PopularGamesContainer">
						<h3>Popular Games</h3>
						<table id="PopularGamesBox">
							<td class="PopularGame">
								<table>
									<td id="ShowcaseBigImages">
										<img src="/images/avatar.png">
										<a id="Play" href=""></a>
									</td>
									<td id="ShowcaseDetails">
										<div id="NameAndCreator">
											<a href="" id="Name">Game Name</a>
											<a href="" id="Creator">Creator</a>
										</div>
										<code>
											Description hi hihi
										</code>
									</td>
								</table>
								
								
							</td>
							<td id="PopularGames">
								<div style="height: 201px;overflow: auto;">
									<img src="/images/avatar.png">
									<img src="/images/avatar.png">
									<img src="/images/avatar.png">
								</div>
								
							</td>
						</div>
					</table>
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>