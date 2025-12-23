<?php 

	ini_set("default_socket_timeout", 15);

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/rcclib.php";

	$directory = $_SERVER['DOCUMENT_ROOT']."/Assemblies/Roblox/Grid/Rcc/";
	$scanned_directory = array_diff(scandir($directory), array('..', '.'));

	foreach($scanned_directory as $file) {
		if(str_contains($file, "wsdl")) {
			continue;
		}
		require $directory.$file;
	}

	
	

	class TheFuckingRenderer {

		public static int $port = 0;
		public static int $port_2008 = 0;
		public static string $address = "";

		public static string $domain = "";
		public static bool $cantuserenderer = false;

		private static function UpdateAndSetConfig(array $renderer_settings) {
			if(self::$domain != $renderer_settings['DOMAIN']) {
				self::$domain = $renderer_settings['DOMAIN'];
			}

			if(self::$port != intval($renderer_settings['RCCPORT'])) {
				self::$port = intval($renderer_settings['RCCPORT']);
			}

			if(self::$port_2008 != intval($renderer_settings['RCCPORT_2008'])) {
				self::$port_2008 = intval($renderer_settings['RCCPORT_2008']);
			}

			if(self::$address != $renderer_settings['RCCIP']) {
				self::$address = $renderer_settings['RCCIP'];
			}

			if(self::$cantuserenderer != boolval($renderer_settings['DISABLED'])) {
				self::$cantuserenderer = boolval($renderer_settings['DISABLED']);
			}
		}

		public static function RenderPlayer(int $id = 0, bool $is2008) {
			$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
			self::UpdateAndSetConfig($settings['renderer']);

			if(self::$cantuserenderer) {
				echo "renderer was disabled?";
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}
			$access = $settings['asset']['ACCESSKEY'];
			try {
				
				$domain = self::$domain;

				$JobId = md5(rand());

				if($is2008) {
					$scriptText = <<<EOT
					local player = game.Players:createLocalPlayer(0)
					player:loadCharacter()
					for _, v in pairs(player.Character:children()) do
						if v.className == "Part" then
							v.BrickColor = BrickColor.new("Light stone grey")
							if v.Name == "Torso" then
								for i, j in pairs(v:children()) do
									if j.className == "Decal" then
										j:Remove()
									end
								end
							end
						end
					end

					workspace.ChildAdded:connect(function(item)
						if item.Name == "Clothing" then
							item.Parent = player.Character
						end
					end)

					workspace:InsertContent("http://$domain/asset/?id=$id&access=$access")

					return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
					EOT;

					$rcc = new RCCServiceSoap(self::$address, self::$port_2008, 'lambda.cam', true);
			
					$base64data = $rcc->execScript(
						$scriptText,
						$JobId,
						5
					);
				} else {
					$scriptText = <<<EOT
					game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
					game:GetService("ScriptContext").ScriptsDisabled = true
					game:GetService("Lighting").Outlines = false

					local player = game.Players:CreateLocalPlayer(0)

					player.CharacterAppearance = "http://$domain/Asset/CharacterFetch.ashx?assetId=$id&access=$access"
					player:LoadCharacter(false)

					return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
					EOT;

					$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);
					$job = new Roblox\Grid\Rcc\Job($JobId);
					$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
					$base64data = $rcc->OpenJob($job, $script);
					$rcc->RenewLease($JobId, 1);
				}
				
			} catch(SoapFault $e) {
				echo "some fault happened ig";
				$base64data = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			return $base64data;
		}

		public static function RenderUser(int $id = 0) {
			$user = User::FromID($id);

			if($user == null) {
				return null;
			}

			self::UpdateAndSetConfig(parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true)['renderer']);

			if(self::$cantuserenderer) {
				echo "renderer was disabled?";
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}
			try {
				$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);

				$domain = self::$domain;

				$JobId = md5(rand());

				$job = new Roblox\Grid\Rcc\Job($JobId);
				$scriptText = <<<EOT
				game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
				game:GetService("ScriptContext").ScriptsDisabled = true
				game:GetService("Lighting").Outlines = false

				local player = game.Players:CreateLocalPlayer(0)

				player.CharacterAppearance = "http://$domain/Asset/CharacterFetch.ashx?userId=$id"
				player:LoadCharacter(false)

				return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
				EOT;

				$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
				$base64data = $rcc->OpenJob($job, $script);
				//$rcc->RenewLease($JobId, 1);
			} catch(SoapFault $e) {
				echo "some fault happened ig";
				$base64data = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			return $base64data;
		}

		public static function RenderMesh(int $id = 0) {
			$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
			self::UpdateAndSetConfig($settings['renderer']);
			
			if(self::$cantuserenderer) {
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			try {
				$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);

				$domain = self::$domain;

				$JobId = md5(rand());
				
				$access = $settings['asset']['ACCESSKEY'];

				$job = new Roblox\Grid\Rcc\Job($JobId);
				$scriptText = <<<EOT
				game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
				game:GetService("ScriptContext").ScriptsDisabled = true
				game:GetService("Lighting").Outlines = false

				local part = Instance.new("Part", workspace)
				part.Size = Vector3.new(4,4,4)

				Instance.new("SpecialMesh", part).MeshId = "http://$domain/asset/?id=$id&access=$access"
				
				return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
				EOT;

				$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
				$base64data = $rcc->OpenJob($job, $script);
				$rcc->RenewLease($JobId, 1);
			} catch(SoapFault $e) {
				$base64data = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			return $base64data;
		}

		public static function RenderPlace(int $id = 0, bool $is2008) {
			$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
			self::UpdateAndSetConfig($settings['renderer']);

			if(self::$cantuserenderer) {
				echo "renderer is disabled?";
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			try{
				$domain = self::$domain;
				$JobId = md5(rand());
				$access = $settings['asset']['ACCESSKEY'];
				$time = time();

				$scriptText = <<<EOT
					game:GetService("ScriptContext").ScriptsDisabled = true
					game:Load("http://$domain/asset/?id=$id&access=$access&time=$time")
					return (game:GetService("ThumbnailGenerator"):Click("PNG", 768, 432, false))
				EOT;

				if($is2008) {
					$rcc = new RCCServiceSoap(self::$address, self::$port_2008, 'lambda.cam', true);
			
					$base64data = $rcc->execScript(
						$scriptText,
						$JobId,
						5
					);
				} else {
					$scriptText = <<<EOT
					game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
					game:GetService("Lighting").Outlines = false
					$scriptText
					EOT;

					$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);
					$job = new Roblox\Grid\Rcc\Job($JobId);
					$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
					$base64data = $rcc->OpenJob($job, $script);
					$rcc->RenewLease($JobId, 1);
				}
				
				
			} catch(SoapFault $e) {
				echo "some fault happened ig";
				echo "\n".self::$address. " " . self::$port;
				$base64data = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			return $base64data;
		}

		public static function RenderModel(int $id = 0, bool $is2008) {
			$settings = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/core/settings.env", true);
			self::UpdateAndSetConfig($settings['renderer']);

			if(self::$cantuserenderer) {
				echo "renderer was disabled?";
				return base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}
			$access = $settings['asset']['ACCESSKEY'];
			try {
				
				$domain = self::$domain;

				$JobId = md5(rand());

				if($is2008) {
					$scriptText = <<<EOT
					workspace:InsertContent("http://$domain/asset/?id=$id&access=$access")
					return (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
					EOT;

					$rcc = new RCCServiceSoap(self::$address, self::$port_2008, 'lambda.cam', true);
			
					$base64data = $rcc->execScript(
						$scriptText,
						$JobId,
						5
					);
				} else {
					$scriptText = <<<EOT
					game:GetService("ContentProvider"):SetBaseUrl("http://$domain/")
					game:GetService("ScriptContext").ScriptsDisabled = true
					game:GetService("Lighting").Outlines = false

					game:GetService("InsertService"):LoadAsset($id).Parent = workspace
					
					local b64 =  (game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true))
					print(b64)
					return b64
					EOT;

					$rcc = new Roblox\Grid\Rcc\RCCServiceSoap(self::$address, self::$port);
					$job = new Roblox\Grid\Rcc\Job($JobId);
					$script = new Roblox\Grid\Rcc\ScriptExecution($JobId."-Script", $scriptText);
					$base64data = $rcc->OpenJob($job, $script);
					$rcc->RenewLease($JobId, 5);
				}
				
			} catch(SoapFault $e) {
				echo "some fault happened ig";
				echo $e;
				$base64data = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/images/unavailable.jpg"));
			}

			return $base64data;
		}

	}

	
	/*$value = TheFuckingRenderer::RenderPlayer();
	echo "<img src='data:image/png;base64,$value'>";*/
?>