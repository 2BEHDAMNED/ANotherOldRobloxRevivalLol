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
		AllowedShittyIDs: [
			"Lights",
			"Cones",
			"FreeItem"
		],
		OpenPurchasePanel: function(panel) {
			$("#PurchasePanel").css("display", "block");
			if(panel == "cones") {
				$("#PurchaseCones").css("display", "block");
			} else if(panel == "lights") {
				$("#PurchaseLights").css("display", "block");
			}
		},
		PurchaseItem: function() {
			if($("#ModalPopup > div:visible").size() == 1) {
				var prompt = $("#ModalPopup > div:visible");

				prompt.css("display","none");
				
				var id = prompt.attr("id").replaceAll("Purchase", "");

				if(this.AllowedShittyIDs.includes(id)) {

					$("#ModalPopup #PurchaseProcessing").css("display", "block");
					window.setTimeout(function() {
						$.post("/api/purchase", { asset_id: Number($("#ModalPopup").attr("data-assetid")), typatransaction: id }, function(data) {
							if(data['error']) {
								ANORRL.Item.Purchasing.PresentError(data['message']);
							} else {
								$("#ModalPopup > div:visible").each(function() {
									$(this).css("display", "none");
								});
								$("#PurchaseSuccess").css("display", "block");
							}
						})
					}, 1500);
				} else {
					ANORRL.Item.Purchasing.PresentError("Something went wrong during the transaction... Sorry...")
				}
			} else {
				ANORRL.Item.Purchasing.PresentError("Something went wrong during the transaction... Sorry...");
			}
		},
		PresentError: function(error) {
			$("#ModalPopup > div:visible").each(function() {
				$(this).css("display", "none");
			});

			var errorpanel = $("#PurchaseError");
			errorpanel.find("#Error").html(error);
			errorpanel.css("display", "block");
		},
		ClosePurchasePanel: function() {
			if($("div#PurchaseProcessing:visible").size() == 0) {
				$("#ModalPopup > div:visible").each(function() {
					$(this).css("display", "none");
				});

				$("#PurchasePanel").css("display", "none");
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