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

const regex = /[^A-Za-z0-9 ]/g;

ANORRL.Character  = {
	CurrentPage: 1,
	CurrentCategory: 8,
	CurrentlyLoadingCrapBruh: false,
	AdvancePager: function() {
		this.LoadWardrobe(this.CurrentCategory, this.CurrentPage + 1);
	},
	DeadvancePager: function() {
		this.LoadWardrobe(this.CurrentCategory, this.CurrentPage - 1);
	},
	LoadWardrobe: function(category, page) {

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
			page = 1;
		}

		var wardrobecontainer = $("#AssetsContainer > table");

		wardrobecontainer.children().each(function() {
			$(this).remove();
		});

		var pagercontainer = $("#AssetsContainer #Paginator");
		
		var backPager = pagercontainer.find("#PrevPager");
		var nextPager = pagercontainer.find("#NextPager");

		$("a[data_category]").each(function() {
			$(this).removeAttr("selected");
		});

		var categorylabel = $("a[data_category="+category+"]").html().toLowerCase().replaceAll("-", "");

		$("a[data_category="+category+"]").attr("selected", "");
		
		ANORRL.ChangeUrl("", "/my/character#"+categorylabel);

		$.get("/api/wardrobe", {c: category, p : page}, function(data) {
			
			var assets = data['assets'];
			ANORRL.Character.CurrentPage = data['page'];
			var current_page = ANORRL.Character.CurrentPage;
			var total_pages = data['total_pages'];

			wardrobecontainer.attr("hidden", true);

			if(assets.length == 0) {
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
				var rowIndex = 0;
				
				for (var key in assets) {
					if(index % 4 == 0 || index == 0) {
						wardrobecontainer.append($("<tr></tr>"));
						if(index % 4 == 0  && index != 0) {
							rowIndex++;
						}
					} 

					var asset = assets[key];

					var td = $("<td></td>");
					var template = $($(".Asset[template]").clone().prop('outerHTML'));
					td.append(template);
					template.removeAttr("template");

					
					var urlname = asset['name'].replaceAll(regex, "").trim().toLowerCase().replaceAll(" ", "-");
					if(urlname == "") {
						urlname = "unnamed";
					}

					template.find("#NameAndThumbs > img").attr("src", "/thumbs/?id="+asset['id']+"&sxy=130");

					template.find("#NameAndThumbs > span").html(asset['name']);
					template.find("#NameAndThumbs").attr("href", "/"+urlname+"-item?id="+asset['id']);

					template.find("#Creator > span").html(asset['creator']['name']);
					template.find("#Creator").attr("href", "/users/"+asset['creator']['id']+"/profile");

					// implement details
					wardrobecontainer.removeAttr("hidden");
					$(wardrobecontainer.find("tr")[rowIndex]).append(td);

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

		ANORRL.Character.CurrentlyLoadingCrapBruh = false;
	}
}

$(function(){

	$("a[data_category]").on("click",function() {
		ANORRL.Character.LoadWardrobe($(this).attr("data_category"), ANORRL.Character.CurrentPage);
	});

	if(window.location.hash != "") {
		var url = window.location.hash;
		url = url.replaceAll("#", "").replaceAll("/","");
		var categories = {
			"hats" : 8,
			"faces" : 18,
			"tshirts" : 11,
			"shirts" : 2,
			"pants" : 12,
			"gears" : 19,
			"outfits" : "outfits",
			"packages" : 32,
			"heads" : 17,
			"torsos" : 27,
			"leftarms" : 29,
			"rightarms": 28,
			"leftlegs" : 30,
			"rightlegs" : 31
		}

		ANORRL.Character.LoadWardrobe(categories[url]);
	} else {
		ANORRL.Character.LoadWardrobe();
	}

	$("#Paginator").find("input").on("change", function() {
		ANORRL.Character.LoadWardrobe(ANORRL.Character.CurrentCategory, Number($(this).val()));
	});
});