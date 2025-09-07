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
	CurrentStatusPage: 1,
	AdvanceFeed: function() {
		this.GrabFeed(this.CurrentStatusPage + 1);
	},
	DeadvanceFeed: function() {
		this.GrabFeed(this.CurrentStatusPage - 1);
	},
	GrabFeed: function(page) {
		if(page === undefined) {
			page = 1;
		}

		var feedscontainer = $("#FeedsContainer #Feeds");

		feedscontainer.children().each(function() {
			$(this).remove();
		});

		var pagercontainer = $("#FeedsContainer #Pager");
		
		var backPager = pagercontainer.find("#BackPager");
		var nextPager = pagercontainer.find("#NextPager");

		$.get("/api/feeds", {p : page}, function(data) {
			if(pagercontainer.css("display") == "none") {
				pagercontainer.css("display", "block");
			}
			var statuses = data['feed'];
			ANORRL.Stuff .CurrentStatusPage = data['page'];
			var current_page = ANORRL.Stuff .CurrentStatusPage;
			var total_pages = data['total_pages'];

			var index = 0;
			
			for (var key in statuses) {
				var status = statuses[key];

				var template = $($("#FeedItem.template").clone().prop('outerHTML'));
				template.removeClass("template");
				template.removeAttr("class");

				if(index % 2 == 0) {
					template.attr("other", "");
				}

				template.find("#Content code").html(status['content']);
				template.find(".User").attr("href", "/users/"+status['poster']['id']+"/profile");
				template.find(".User #Name").html(status['poster']['name']);
				template.find("#Content #DatePosted #Date").html(ANORRL.GetTimeSince(status['time_posted']));
				
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

			pagercontainer.find("#PageCounter").html("Page " + current_page + " of " + total_pages);
		});
	}
}

$(function(){
	ANORRL.Stuff .GrabFeed();
});