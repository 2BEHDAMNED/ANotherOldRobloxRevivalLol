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

var allowedcategories = {
	"shirts": 11,
	"tshirts": 2,
	"pants": 12,
	"audio": 3,
	"decals": 13,
	"models": 10,
	"places": 9,
}

ANORRL.Stuff  = {
	CurrentPage: 1,
	AdvanceFeed: function() {
		this.GrabFeed(this.CurrentPage + 1);
	},
	DeadvanceFeed: function() {
		this.GrabFeed(this.CurrentPage - 1);
	},
	GrabAssets: function(category, page) {

		var loadingMessage = $("#AssetsContainer #StatusText #Loading");
		var emptyMessage = $("#AssetsContainer #StatusText #NoAssets");

		emptyMessage.css("display", "none");
		loadingMessage.css("display", "block");

		if(category === undefined) {
			category = 8;
		}
		if(page === undefined) {
			page = 1;
		}

		var feedscontainer = $("#AssetsContainer > table");

		feedscontainer.children().each(function() {
			$(this).remove();
		});

		var pagercontainer = $("#AssetsContainer #Paginator");
		
		var backPager = pagercontainer.find("#PrevPager");
		var nextPager = pagercontainer.find("#NextPager");

		$("li[data_category]").each(function() {
			$(this).removeAttr("selected");
		});

		$("li[data_category="+category+"]").attr("selected", "");
		if(allowedcategories[$("li[data_category="+category+"]").find("a").html().toLowerCase().replaceAll("-", "")] != undefined) {
			$($("#CreateArea").find("a")[0]).removeAttr("disabled");
			$($("#CreateArea").find("a")[0]).attr("href","/create/"+$("li[data_category="+category+"]").find("a").html().toLowerCase().replaceAll("-", ""));
		} else {
			$($("#CreateArea").find("a")[0]).removeAttr("href");
			$($("#CreateArea").find("a")[0]).attr("disabled", "true");
		}
		
		ChangeUrl("", "/my/stuff#"+$("li[data_category="+category+"]").find("a").html().toLowerCase().replaceAll("-", ""));

		$.get("/api/stuff", {c: category, p : page}, function(data) {
			
			var assets = data['assets'];
			ANORRL.Stuff.CurrentPage = data['page'];
			var current_page = ANORRL.Stuff.CurrentPage;
			var total_pages = data['total_pages'];

			feedscontainer.attr("hidden", true);

			if(assets.length == 0) {
				if(pagercontainer.css("display") == "block") {
					pagercontainer.css("display", "none");
				}
				loadingMessage.css("display", "none");
				emptyMessage.css("display", "block");

				emptyMessage.find("#AssetType").html($("li[data_category="+category+"]").find("a").html().toLowerCase());

				
			} else {
				loadingMessage.css("display", "none");
				if(pagercontainer.css("display") == "none") {
					pagercontainer.css("display", "block");
				}

				var index = 0;
				var rowIndex = 0;
				
				for (var key in assets) {
					if(index % 4 == 0 || index == 0) {
						feedscontainer.append($("<tr></tr>"));
						if(index % 4 == 0  && index != 0) {
							rowIndex++;
						}
					} 

					var asset = assets[key];

					var td = $("<td></td>");
					var template = $($(".Asset[template]").clone().prop('outerHTML'));
					td.append(template);
					template.removeAttr("template");

					if(asset['cost']['cones'] + asset['cost']['lights'] == 0) {
						template.find("#Pricing").children().each(function() {
							$(this).remove();
						});
						template.find("#Pricing").append($("<span id=\"FreeTag\">Free</span>"))
					} else {

						template.find("#Pricing").attr("oneprice", "true");

						if(asset['cost']['cones'] == 0) {
							template.find("#Pricing").find("#Cones").remove();
						}

						if(asset['cost']['lights'] == 0) {
							template.find("#Pricing").find("#Lights").remove();
						}
					}

					template.find("#NameAndThumbs > img").attr("src", "/thumbs/?id="+asset['id']+"&sxy=130");

					template.find("#NameAndThumbs > span").html(asset['name']);
					template.find("#NameAndThumbs").attr("href", "/"+asset['name'].replace(new RegExp("[^A-z0-9]"),"").trim().replaceAll(" ", "-").toLowerCase()+"-item?id="+asset['id']);

					template.find("#Creator > span").html(asset['creator']['name']);
					template.find("#Creator").attr("href", "/users/"+asset['creator']['id']+"/profile");

					// implement details
					feedscontainer.removeAttr("hidden");
					$(feedscontainer.find("tr")[rowIndex]).append(td);

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

				pagercontainer.find("input").val(current_page);
				pagercontainer.find("#Pages").html(total_pages);
			}
		});
	}
}

function ChangeUrl(title, url) {
    if (typeof (history.pushState) != "undefined") {
        var obj = { Title: title, Url: url };
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        window.location.href = url;
    }
}

$(function(){

	

	$("li[data_category]").on("click",function() {
		ANORRL.Stuff.GrabAssets($(this).attr("data_category"), ANORRL.Stuff.CurrentPage);
	});

	if(window.location.hash != "") {
		var url = window.location.hash;
		url = url.replaceAll("#", "").replaceAll("/","");
		var categories = {
			"hats": 8,
			"faces": 18,
			"shirts": 11,
			"tshirts": 2,
			"pants": 12,

			"audio": 3,
			"decals": 13,
			"models": 10,
			"places": 9,

			"gears": 19,
			"badges": 21,
			"gamepasses": 34,
			"packages": 32,
		}

		ANORRL.Stuff.GrabAssets(categories[url]);
	} else {
		ANORRL.Stuff.GrabAssets();
	}
	
});