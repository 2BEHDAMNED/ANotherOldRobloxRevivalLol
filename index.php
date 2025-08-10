<!DOCTYPE html>
<html>
	<head>
		<title>Welcome to ANORRL!</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/AllCSS.css">
		<script src="/js/jquery.js"></script>
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
				height:42px;

				background: #171717;
				
			}

			#ShowcaseBox #Carousel img {
				display:inline;
				width:42px;
				height:42px;
			}

			#ShowcaseBox #Carousel img:hover,
			#ShowcaseBox #Carousel img.selected {
				background: rgba(255, 255, 255, 0.2);
			}
		</style>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="IntroductionBox">
						<h2>What is ANORRL?</h2>
						<p><b>ANORRL</b> is a 2012 "Old Roblox Revival" which aims to bring a fresh new look at the past!</p>
						<p><b>ANORRL</b> stands for <code>"<b>AN</b>other <b>O</b>ld <b>R</b>oblox <b>R</b>evival <b>L</b>ol"</code></p>
						<h3>So what is a "Old Roblox Revival"</h3>
						<p>
							It is a project that aims to recreate and bring life into relatively old clients that ROBLOX has created in the past.<br>
							<b>Please note that</b>: These revivals do tend to be private (along with this one) as to ensure the maximum security because not only are These
							clients old, they are also insecure!
						</p>
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
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>