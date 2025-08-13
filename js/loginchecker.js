// SURELY THERE'S A BETTER WAY OF DOING THIS

if(typeof(ANORRL) == "undefined") {
	ANORRL = {}
}

ANORRL.Login = {
	pattern: /^[a-zA-Z0-9]{3,20}$/,
	
	IsUsernameValid: function(input) {
		return $.trim(input).length != 0 && ANORRL.Login.pattern.test($.trim(input));
	},

	CheckUsername: function(element, input) {
		
		if(input.length != 0) {
			if(ANORRL.Login.IsUsernameValid(input)) {
				$("#v_username").html("");
				$(element).removeClass("Invalid");
				$(element).addClass("Valid");
			} else {
				$("#v_username").html("a-z A-Z 0-9 and 3-20 characters only!");
				$(element).addClass("Invalid");
				$(element).removeClass("Valid");
			}
		} else {
			$("#v_username").html("");
			$(element).removeClass("Valid");
			$(element).removeClass("Invalid");
		}
	},
	CheckPassword: function(element, input) {
		if(input.length != 0) {
			if(input.length >= 7) {
				$("#v_password").html("");
				$(element).removeClass("Invalid");
				$(element).addClass("Valid");
			} else {
				$("#v_password").html("Password must be minimum 7 characters!");
				$(element).addClass("Invalid");
				$(element).removeClass("Valid");
			}
		} else {
			$("#v_password").html("");
			$(element).removeClass("Valid");
			$(element).removeClass("Invalid");
		}
	}
}

$(function(){
	$("#ANORRL_Login_Username").on("input change", function() {
		ANORRL.Login.CheckUsername(this, $(this).val());
	});
	$("#ANORRL_Login_Password").on("input change", function() {
		ANORRL.Login.CheckPassword(this, $(this).val());
	});

	$("form").submit(function (e) {
		// Basically, IE literally doesn't want to check if anything has been changed to an input unless directly by keys
		// This just runs all the checks before submission.
		ANORRL.Login.CheckUsername(document.getElementById("ANORRL_Login_Username"), $("#ANORRL_Login_Username").val());
		ANORRL.Login.CheckPassword(document.getElementById("ANORRL_Login_Password"), $("#ANORRL_Login_Password").val());
		if(!($(".Invalid").length == 0 && $(".Valid").length == 2)) {
			e.preventDefault();
			alert("Holy shit you have so much wrong");
		}
	});

});