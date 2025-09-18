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

var categoryFileTypes = {
	1:"image/*",
	11:"image/*",
	18:"image/*",
	2: "image/*",
	12:"image/*",
	3: ".mp3",
	13:"image/*",
	10:".rbxm",
	4:"*",
	5:".txt,.lua",
	9: ".rbxl",
}

const regex = /[^A-Za-z0-9 ]/g;

ANORRL.Create  = {
	CurrentPage: 1,
	CurrentCategory: 11,
	CurrentlyLoadingCrapBruh: false,
	AdvancePager: function() {
		this.GrabAssets(this.CurrentCategory, this.CurrentPage + 1);
	},
	DeadvancePager: function() {
		this.GrabAssets(this.CurrentCategory, this.CurrentPage - 1);
	},
	GrabAssets: function(category, page) {

		if(this.CurrentlyLoadingCrapBruh) {
			return;
		} else {
			this.CurrentlyLoadingCrapBruh = true;
		}

		var loadingMessage = $("#AssetsContainer #StatusText #Loading");
		var emptyMessage = $("#AssetsContainer #StatusText #NoAssets");

		emptyMessage.css("display", "none");
		loadingMessage.css("display", "block");

		if(category === undefined) {
			category = this.CurrentCategory;
		} else {
			this.CurrentCategory = category;
		}
		if(page === undefined) {
			page = this.CurrentPage;
		}

		var feedscontainer = $("#AssetsContainer > table");
		feedscontainer.attr("hidden", "true");

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
		ChangeUrl("", "/create/"+$("li[data_category="+category+"]").find("a").html().toLowerCase().replaceAll("-", ""));

		var categorylabel = $("li[data_category="+category+"]").find("a").html();
		if(categorylabel.endsWith("s") && categorylabel != "Pants") {
			categorylabel = categorylabel.substring(0, categorylabel.length-1);
		}
		$("#TypaLabel").html(categorylabel);
		if(categorylabel == "Pants" || categorylabel == "Shirt") {
			$("#TypaLabel").html(categorylabel + " (<a target='_blank' href='/images/"+categorylabel+"Template.png'>Template</a>)");
		}
		$("#files").attr("accept", categoryFileTypes[category]);

		var warning = $("#InfoWarning");

		if(category == 10 || category == 9) {
			warning.css("display", "block");
		} else {
			warning.css("display", "none");
		}

		$.get("/api/stuff", {c: category, p : page}, function(data) {
			
			var assets = data['assets'];
			ANORRL.Create.CurrentPage = data['page'];
			var current_page = ANORRL.Create.CurrentPage;
			var total_pages = data['total_pages'];

			

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

					if(category != 9) {
						if(asset['cost']['cones'] + asset['cost']['lights'] == 0) {
							template.find("#Pricing").attr("oneprice", "true");
							template.find("#Pricing").children().each(function() {
								$(this).remove();
							});
							template.find("#Pricing").append($("<span id=\"FreeTag\">Free</span>"))
						} else {

							if(asset['cost']['cones'] == 0) {
								template.find("#Pricing #Cones").remove();
							} else {
								template.find("#Pricing #Cones #Costing").html(asset['cost']['cones']);
							}

							if(asset['cost']['lights'] == 0) {
								template.find("#Pricing #Lights").remove();
							} else {
								template.find("#Pricing #Lights #Costing").html(asset['cost']['lights']);
							}


							if(asset['cost']['lights'] != 0 && asset['cost']['cones'] != 0) {
								template.find("#Pricing").removeAttr("oneprice");
							} else {
								template.find("#Pricing").attr("oneprice", "true");
							}

						}
					} else {
						template.find("#Pricing").remove();
					}
					

					template.find("#NameAndThumbs > img").attr("src", "/thumbs/?id="+asset['id']+"&sxy=130");

					template.find("#NameAndThumbs > span").html(asset['name']);
					template.find("#NameAndThumbs").attr("href", "/"+asset['name'].replaceAll(regex,"").trim().replaceAll(" ", "-").toLowerCase()+"-item?id="+asset['id']);

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

			ANORRL.Create.CurrentlyLoadingCrapBruh = false;
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
		ANORRL.Create.GrabAssets($(this).attr("data_category"));
	});

	var url = window.location.pathname;
	url = url.replaceAll("/create/", "").replaceAll("/","");

	$("#files").change(function() {
		filename = this.files[0].name;
		$("#filename").html(filename);
	});

	// TODO: Move this out of here this is such a disaster waiting to come
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
		"images": 1,
		"packages": 32,
		"meshes": 4,
		"lua": 5,
	}

	ANORRL.Create.GrabAssets(categories[url]);
});

