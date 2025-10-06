if(ANORRL == undefined) {
	ANORRL = {};
}

const regex = /[^A-Za-z0-9 ]/g;

ANORRL.Games = {
	CurrentPage: 1,
	CurrentQuery: "",
	LoadNoQueryGames: function(page) {
		if(page === undefined) {
			page = 1;
		}

		this.LoadGames("", page);
	},
	Submit: function() {
		this.LoadGames($("#SearchBox[name=query]").val(), 1);
	},
	NextPage: function() {
		this.LoadGames(this.CurrentQuery, this.CurrentPage + 1);
	},
	PrevPage: function() {
		this.LoadGames(this.CurrentQuery, this.CurrentPage - 1);
	},
	LoadGames: function(query, page, filter) {

		if(query === undefined) {
			query = this.CurrentQuery;
		} else {
			this.CurrentQuery = query;
		}
		if(page === undefined) {
			page = this.CurrentPage;
		} else {
			this.CurrentPage = page;
		}

		var loadingMessage = $("#Games #StatusText #Loading");
		var emptyMessage = $("#Games #StatusText #NoAssets");

		emptyMessage.css("display", "none");
		loadingMessage.css("display", "block");


		var gamescontainer = $("#ContainerThingy");

		gamescontainer.children().each(function() {
			$(this).remove();
		});

		var pagercontainer = $("#Games #Paginator");
		
		var backPager = pagercontainer.find("#BackPager");
		var nextPager = pagercontainer.find("#NextPager");

		$.get("/api/games", { q: query, p : page}, function(data) {

			var games = data['games'];
			ANORRL.Games.CurrentPage = data['page'];
			var current_page = ANORRL.Games.CurrentPage;
			var total_pages = data['total_pages'];

			gamescontainer.attr("hidden", true);

			if(games.length == 0) {
				if(pagercontainer.css("display") == "block") {
					pagercontainer.css("display", "none");
				}
				loadingMessage.css("display", "none");
				emptyMessage.css("display", "block");
				
			} else {
				loadingMessage.css("display", "none");
				if(pagercontainer.css("display") == "none") {
					pagercontainer.css("display", "block");
				}

				var index = 0;
				
				for (var key in games) {
					var asset = games[key];
					var template = $($(".Game[template]").clone().prop('outerHTML'));
					template.removeAttr("template");
					
					var urlname = asset['name'].replaceAll(regex, "").trim().toLowerCase().replaceAll(" ", "-");
					if(urlname == "") {
						urlname = "unnamed";
					}

					template.find("a").on("click", function(ev) {
						ev.stopPropagation(); // overrides container click so only this action is performed
						window.location.href = $(this).attr("href");
					});

					template.on("click", function() {
						window.location.href = "/game/"+$(this).attr("data-placeid"); 
					});

					template.find("#ImageContainer > img").attr("src", "/thumbs/?id="+asset['id']+"&sx=189&sy=106");
					template.find("#GameName").attr("href", urlname+"-place?id="+asset['id']);
					template.find("#GameName").html(asset['name']);

					template.find("#GameCreator").html(asset['creator']['name']);
					template.find("#GameCreator").attr("href", "/users/"+asset['creator']['id']+"/profile");

					template.attr("data-placeid", asset['id']);

					gamescontainer.append(template);

					// implement details
					gamescontainer.removeAttr("hidden");
					index++;
				}

				if(current_page == 1) {
					backPager.css("display", "none");
				} else {
					backPager.css("display", "inline");
				}

				if(current_page == total_pages) {
					nextPager.css("display", "none");
				} else {
					nextPager.css("display", "inline");
				}

				ANORRL.Games.CurrentPage = current_page;
				pagercontainer.find("input").val(current_page);
				pagercontainer.find("#Counter").html(total_pages);
			}

			//ANORRL.Stuff.CurrentlyLoadingCrapBruh = false;
		});
	}
};

$(function() {
	ANORRL.Games.LoadNoQueryGames();

	$("#Games #Paginator").find("input").on("change", function() {
		ANORRL.Games.LoadGames(this.CurrentQuery, Number($(this).val()));
	});
})