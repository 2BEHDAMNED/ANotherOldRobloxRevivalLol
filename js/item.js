if(ANORRL == undefined) {
	ANORRL = {};
}

ANORRL.Item = {
	Favourite: function(assetid) {
		$.post("/api/favourite", { asset : assetid }, function(data) {
			if(data['error']) {
				alert("Error: " + data['reason']);
			} else {
				window.location.reload();
			}
		});
	}
};

$(function() {
	$(".FavouriteButton").click(function() {
		ANORRL.Item.Favourite($(this).attr("data-assetid"));
	});
})