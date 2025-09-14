<?php 

	ini_set('soap.wsdl_cache_enabled',0);
	ini_set('soap.wsdl_cache_ttl',0);

	$directory = $_SERVER['DOCUMENT_ROOT']."/Assemblies/Roblox/Grid/Rcc/";
	$scanned_directory = array_diff(scandir($directory), array('..', '.'));

	foreach($scanned_directory as $file) {
		require $directory.$file;
	}

	class TheFuckingRenderer {

		public static int $port = 64989;
		public static string $address = "localhost";

		public static function RenderPlayer() {
			$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);
			if ($rcc instanceof SoapFault) {
				echo "yeah it went all wrong smh...";
				die();
			}

			$JobId = md5(rand());

			$job = new Roblox\Grid\Rcc\Job($JobId);
			$scriptText = <<<EOT
			game:GetService("ContentProvider"):SetBaseUrl("http://localhost:81/")
			game:GetService("ScriptContext").ScriptsDisabled = true
			game:GetService("Lighting").Outlines = false

			local player = game.Players:CreateLocalPlayer(0)

			player:LoadCharacter()

			return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
			EOT;
			$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
			$base64data = $rcc->OpenJob($job, $script);
			$rcc->RenewLease($JobId, 5);
			
			return $base64data;
		}

	}

	/*$value = TheFuckingRenderer::RenderPlayer();
	echo "<img src='data:image/png;base64,$value'>";*/
?>