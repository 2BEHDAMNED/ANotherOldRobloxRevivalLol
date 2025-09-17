<?php 

	ini_set('soap.wsdl_cache_enabled',0);
	ini_set('soap.wsdl_cache_ttl',0);

	$directory = $_SERVER['DOCUMENT_ROOT']."/Assemblies/Roblox/Grid/Rcc/";
	$scanned_directory = array_diff(scandir($directory), array('..', '.'));

	foreach($scanned_directory as $file) {
		if(str_contains($file, "wsdl")) {
			continue;
		}
		require $directory.$file;
	}

	
	

	class TheFuckingRenderer {

		public static int $port = 64989;
		public static string $address = "localhost";

		public static string $domain = "arl.lambda.cam";
		public static bool $cantuserenderer = false;

		public static function RenderPlayer(int $id = 0) {

			if(self::$cantuserenderer) {
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);
			if ($rcc instanceof SoapFault) {
				echo "yeah it went all wrong smh...";
				die();
			}

			$domain = self::$domain;

			$JobId = md5(rand());

			$job = new Roblox\Grid\Rcc\Job($JobId);
			$scriptText = <<<EOT
			game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
			game:GetService("ScriptContext").ScriptsDisabled = true
			game:GetService("Lighting").Outlines = false

			local player = game.Players:CreateLocalPlayer(0)

			player.CharacterAppearance = "http://arl.lambda.cam/Asset/CharacterFetch.ashx?assetId=$id"
			player:LoadCharacter(false)

			return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
			EOT;

			$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
			$base64data = $rcc->OpenJob($job, $script);
			$rcc->RenewLease($JobId, 1);

			return $base64data;
		}

		public static function RenderMesh(int $id = 0) {
			if(self::$cantuserenderer) {
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);
			if ($rcc instanceof SoapFault) {
				echo "yeah it went all wrong smh...";
				die();
			}

			$domain = self::$domain;

			$JobId = md5(rand());

			$job = new Roblox\Grid\Rcc\Job($JobId);
			$scriptText = <<<EOT
			game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
			game:GetService("ScriptContext").ScriptsDisabled = true
			game:GetService("Lighting").Outlines = false

			local part = Instance.new("Part", workspace)
			part.Size = Vector3.new(4,4,4)

			Instance.new("SpecialMesh", part).MeshId = "http://arl.lambda.cam/asset/?id=$id"
			
			return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
			EOT;

			$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
			$base64data = $rcc->OpenJob($job, $script);
			$rcc->RenewLease($JobId, 1);

			return $base64data;
		}

		public static function RenderPlace(int $id = 0) {
			if(self::$cantuserenderer) {
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);
			if ($rcc instanceof SoapFault) {
				echo "yeah it went all wrong smh...";
				die();
			}

			$domain = self::$domain;

			$JobId = md5(rand());

			$job = new Roblox\Grid\Rcc\Job($JobId);
			$scriptText = <<<EOT
			game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
			game:GetService("ScriptContext").ScriptsDisabled = true
			game:GetService("Lighting").Outlines = false

			game:Load("http://arl.lambda.cam/asset/?id=$id")
			
			return (game:GetService("ThumbnailGenerator"):Click("PNG", 768, 432, false))
			EOT;

			$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
			$base64data = $rcc->OpenJob($job, $script);
			$rcc->RenewLease($JobId, 1);

			return $base64data;
		}

	}

	
	/*$value = TheFuckingRenderer::RenderPlayer();
	echo "<img src='data:image/png;base64,$value'>";*/
?>