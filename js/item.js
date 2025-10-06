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
	},
	Purchasing: {
		OpenPurchasePanel: function(panel) {
			$("#PurchasePanel").css("display", "block");
			if(panel == "cones") {
				$("#PurchaseCones").css("display", "block");
			} else if(panel == "lights") {
				$("#PurchaseLights").css("display", "block");
			}
		},
		PurchaseItem: function() {
			if($("#ModalPopup > div:visible").size() != 0) {
				$("#ModalPopup > div:visible").each(function() {
					$(this).css("display", "none");
				});
			}
			
			$("#ModalPopup #PurchaseProcessing").css("display", "block");
		},
		ClosePurchasePanel: function() {
			if($("div#PurchaseProcessing:visible").size() == 0) {
				$("#PurchasePanel").css("display", "none");

				$("#PurchaseLights").css("display", "none");
				$("#PurchaseCones").css("display", "none");
			}

		}
	}
};

$(function() {
	$(".FavouriteButton").click(function() {
		ANORRL.Item.Favourite($(this).attr("data-assetid"));
	});

	$("#ModalPopup").on("click", function(evt) {
		evt.stopPropagation();
	})

	$("#PurchasePanel").on("click", function() {
		ANORRL.Item.Purchasing.ClosePurchasePanel();
	})
})