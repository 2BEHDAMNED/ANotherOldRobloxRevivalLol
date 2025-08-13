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