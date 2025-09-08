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

		$.get("/api/stuff", {c: category, p : page}, function(data) {
			
			var assets = data['assets'];
			ANORRL.Stuff.CurrentPage = data['page'];
			var current_page = ANORRL.Stuff.CurrentPage;
			var total_pages = data['total_pages'];

			var index = 0;

			if(assets.length == 0) {
				if(pagercontainer.css("display") == "block") {
					pagercontainer.css("display", "none");
				}
				loadingMessage.css("display", "none");
				emptyMessage.css("display", "block");

				emptyMessage.find("#AssetType").html($("li[data_category="+category+"]").find("a").html().toLowerCase());

				
			} else {
				if(pagercontainer.css("display") == "none") {
					pagercontainer.css("display", "block");
				}
				
				for (var key in assets) {
					var asset = assets[key];

					var template = $($(".Asset[template]").clone().prop('outerHTML'));
					template.removeAttr("template");
					
					// implement details

					feedscontainer.append($(template));

					index += 1;
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

$(function(){

	$("li[data_category]").on("click",function() {
		ANORRL.Stuff.GrabAssets($(this).attr("data_category"), ANORRL.Stuff.CurrentPage);
	});
	ANORRL.Stuff.GrabAssets();
});