if(ANORRL == undefined) {
	ANORRL = {};
}

ANORRL.Games = {
	LoadNoQueryGames: function(page) {
		if(page === undefined) {
			page = 1;
		}

		this.LoadGames("", page);
	},
	Submit: function() {
		this.LoadGames($("#SearchBox[name=query]").val(), 1);
	},
	LoadGames: function(query, page, filter) {
		
	}
};

$(function() {
	ANORRL.Games.LoadNoQueryGames();
})