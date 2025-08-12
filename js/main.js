ANORRL = {
	Logout: function() {
		$.get( "/api/logout", function() {
			window.location.reload();
		});
	}
};