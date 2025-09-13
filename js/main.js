ANORRL = {
	Logout: function() {
		$.get( "/api/logout", function() {
			window.location.reload();
		});
	},
	GetInternetExplorerVersion: function() {
		// Returns the version of Internet Explorer or a -1
		// (indicating the use of another browser).
	
		var rv = -1; // Return value assumes failure.
	
		if (navigator.appName == 'Microsoft Internet Explorer')
		{
			var ua = navigator.userAgent;
			var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
	
			if (re.exec(ua) != null) rv = parseFloat( RegExp.$1 );
		}
		return rv;
	},
	GetDateFormatFromTimestamp: function(timestamp) {
		var offset = (new Date().getTimezoneOffset())*(60);
		var time = timestamp;
		var d = new Date(time * 1000);
		return ("0" + d.getDate()).slice(-2) + "/" + ("0"+(d.getMonth()+1)).slice(-2) + "/" + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
	},
	GetTimeSince: function(date) {
		var seconds = Math.floor((Date.now()/1000) - date);
		var interval = seconds / 31536000;
	  
		if (interval > 1) {
			if(Math.floor(interval) == 1) 
				return Math.floor(interval) + " year";
			return Math.floor(interval) + " years";
		}
		interval = seconds / 2592000;
		if (interval > 1) {
			if(Math.floor(interval) == 1) 
				return Math.floor(interval) + " month";
			return Math.floor(interval) + " months";
		}
		interval = seconds / 86400;
		if (interval > 1) {
			if(Math.floor(interval) == 1) 
				return Math.floor(interval) + " day";
			return Math.floor(interval) + " days";
		}
		interval = seconds / 3600;
		if (interval > 1) {
			if(Math.floor(interval) == 1) 
				return Math.floor(interval) + " hour";
			return Math.floor(interval) + " hours";
		}
		interval = seconds / 60;
		if (interval > 1) {
			if(Math.floor(interval) == 1) 
				return Math.floor(interval) + " minute";
			return Math.floor(interval) + " minutes";
		}
		return Math.floor(seconds) + " seconds";
	},
	HideMobileWarning: function() {
		$(".DisplayMobileWarning").remove();
		$.cookie("MobileKnowsThis", "true");
	}
};


if(ANORRL.GetInternetExplorerVersion() != -1) {
	$(function() {
		$("input[placeholder]").each(function() {
			this.value = $(this).attr('placeholder');
		});
		$("input[placeholder]").focus(function() {
			if (this.value == $(this).attr('placeholder')) {
				this.value = '';
			} 
		}).blur(function() {
			if (this.value == '')
				this.value = $(this).attr('placeholder'); 
		});
	})
}

$(function() {
	if('ontouchstart' in document.documentElement) {
		if($.cookie("MobileKnowsThis") == undefined) {
			$(".DisplayMobileWarning").css("display", "block");
		}
		
	} else {
		$(".DisplayMobileWarning").css("display", "none");
	}
})