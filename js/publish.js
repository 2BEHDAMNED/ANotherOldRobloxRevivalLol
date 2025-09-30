if(ANORRL == undefined) {
	ANORRL = {};
}

ANORRL.Publish = {
	HandleAction: function(actionparams) {
		if(actionparams != "createnew" && actionparams != 0) {
			// perform publish.
			try {
				window.external.SaveUrl('http://localhost/Data/Upload.ashx?assetid='+actionparams);
				document.getElementById("Uploading").style.display='none';
				document.getElementById("Confirmation").style.display='block';
			} catch (ex) {
				try {
					window.external.SaveUrl('http://localhost/Data/Upload.ashx?assetid='+actionparams);
					document.getElementById("Uploading").style.display='none';
					document.getElementById("Confirmation").style.display='block';
				} catch (ex2) {
					document.getElementById("Uploading").style.display='none';
					document.getElementById("Failure").style.display='block';
				}
			}
		} else {
			window.location.href = "/IDE/PublishNewPlace.aspx";
		}
		//alert(actionparams);
	}
};

$(function() {
	$("#PublishPlaces .Place").each(function() {
		$(this).click(function() {
			ANORRL.Publish.HandleAction($(this).attr("data-placeid"));
		});
	})
})