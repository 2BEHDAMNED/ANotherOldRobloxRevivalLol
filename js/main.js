Iota = {
	SearchCheck: function(elem) {
		var queries = $("#Topbar #Search div#Queries");
		if(elem.val() != "") {

			queries.children().each(function() {
				var link = $(this).find("a");

				link.html("Search &quot;"+elem.val()+"&quot; in " + link.attr("typa"));

				if(link.attr("typa") == "People") {
					link.attr("href", "/people?query="+elem.val());
				}
				
			});

			if(elem.is(":focus")) {
				queries.css("display", "block");
			} else {
				if(queries.find("#Query a:hover").length == 0)
					queries.css("display", "none");
			}
		} else {
			queries.css("display", "none");
		}
	},

	Initialise: function() {
		$("#Topbar #Search").delegate("input", "focus blur input change", function() {
			Iota.SearchCheck($(this));
		});

		$("#Topbar #Controls #SettingsButton").delegate("*","click", function() {
			var settingspanel = $("#Topbar #Controls #SettingsPanel");
			if(settingspanel.css("display") == "none") {
				$(this).css("transform", "rotate(45deg)");
				settingspanel.css("display", "block")
			} else {
				$(this).css("transform", "rotate(0deg)");
				settingspanel.css("display", "none")
			}
		});
	
		$('#Topbar #Controls #SettingsPanel').hover(undefined, function () {
			$("#Topbar #Controls #SettingsButton img").css("transform", "rotate(0deg)");
			$(this).css("display", "none");
		});

		$('img').imageloader();
	},

	Logout: function() {
		$.get( "/api/logout", function() {
			window.location.reload();
		});
	}
};

$(function() {
	Iota.Initialise();
});