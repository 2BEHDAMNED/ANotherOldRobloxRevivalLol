if(typeof(ANORRL) == "undefined") {
	ANORRL = {}
}

if (!Object.keys) {
	Object.keys = function(obj) {
		var keys = [];
		for (var i in obj) {
			if (obj.hasOwnProperty(i)) {
				keys.push(i);
			}
		}
		return keys;
	};
}

/*
<table class="Server">
	<td id="PlayersBox">
		<a title="Player" id="Player" href="/"><img src="/images/avatar.png"></a>
		
	</td>
	<td id="JoinBox" width="150">
		<div>
			1 / 4
		</div>
		<div>
			<button>Join Server</button>
		</div>
	</td>
</table>
*/

//

ANORRL.PlaceLauncher  = {
	CurrentlyLoadingCrapBruh: false,
	LetsJoinAndPlay: function(placeId) {
		$.post("/api/ticketer", {placeID: placeId}, function(data) {
			alert("Launching!");
			window.open("anorrl-player-lambda:1+placelauncherurl:http%3A%2F%2Farl.lambda.cam%2Fgame%2FPlaceLauncher.ashx?sessionID="+data+"+placeid:"+placeId+"+launchmode:play+gameinfo:0");
		});
	},
	
	CreateServerElement: function(serverId) {
		var table = $("<table><tr></tr></table>");
		table.addClass("Server");

		var trRow = table.find("tr");

		var playersBox = $("<td></td>");
		playersBox.attr("id", "PlayersBox");
		playersBox.appendTo(trRow);

		var joinBox = $("<td></td>");
		joinBox.attr("id", "JoinBox");
		joinBox.attr("width", "150");
		
		joinBox.append("<div id=\"PlayerCount\">0 / 0</div>");
		
		var joinArea = $("<div id=\"JoinArea\"></div>");

		var joinButton = $("<button>Join Server</button>");

		var joinLink = "";

		$.post("/api/ticketer", {serverId: serverId}, function(data) {
			joinLink = data;
		});

		joinButton.on("click", function() {
			window.open("anorrl://"+joinLink, "_blank");
		});

		joinArea.append(joinButton);
		joinBox.append(joinArea);
		
		joinBox.appendTo(trRow);

		return table;
	},

	GrabGameservers: function(placeid) {

		if(this.CurrentlyLoadingCrapBruh) {
			return;
		} else {
			this.CurrentlyLoadingCrapBruh = true;
		}

		var serversContainer = $("#InfoBox #ServersBox");
		//serversContainer.attr("hidden", "true");

		serversContainer.children().each(function() {
			$(this).remove();
		});

		$.get("/api/gameservers", {placeId: placeid}, function(data) {
			
			var servers = data;

			if(servers.length == 0) {
			} else {

				for (var key in servers) {
					console.log(servers[key]);

					var server = servers[key];
					var players = server['players'];

					var template = ANORRL.PlaceLauncher.CreateServerElement(server['id']);
					for (var pkey in players) {
						var player = players[pkey];
						template.find("#PlayersBox").append("<a title=\""+player['name']+"\" id=\"Player\" href=\"/users/"+player['id']+"/profile\"><img src=\"/images/avatar.png\"></a>");
					}
					
					serversContainer.append(template);
				}
			}

			ANORRL.PlaceLauncher.CurrentlyLoadingCrapBruh = false;
		});
	}
}