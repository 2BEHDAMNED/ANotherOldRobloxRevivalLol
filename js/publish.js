if(ANORRL == undefined) {
	ANORRL = {};
}

ANORRL.Publish = {
	HandleAction: function(actionparams) {
		alert(actionparams);
	}
};

$(function() {
	$("#PublishPlaces .Place").each(function() {
		$(this).click(function() {
			ANORRL.Publish.HandleAction($(this).attr("data-placeid"))
		})
		
	})
})