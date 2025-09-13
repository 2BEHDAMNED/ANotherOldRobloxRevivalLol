if(ANORRL == undefined) {
	ANORRL = {};
}

ANORRL.EditItem = {
	ShowPricingArea: function(show) {
		 $(".ThePricing").each(function() {
			if(show) {
				$(this).css("display", "revert");
			} else {
				$(this).css("display", "none");
			}
		 })
	}
};

$(function() {
	$("#OnSaleCheckbox").on("change", function() {
		ANORRL.EditItem.ShowPricingArea($(this).is(":checked"));
	})
	ANORRL.EditItem.ShowPricingArea($("#OnSaleCheckbox").is(":checked"));
})