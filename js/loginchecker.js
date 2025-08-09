// SURELY THERE'S A BETTER WAY OF DOING THIS

var pattern = /^[a-zA-Z0-9]{3,20}$/;

function IsUsernameValid(input) {
	return input.trim().length != 0 && pattern.test(input);
}

function CheckUsername(element, input) {
	if(input.length != 0) {
		if(IsUsernameValid(input)) {
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
}

function CheckPassword(element, input) {
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

$(function(){
	$("#Iota_Login_Username").on("input", function() {
		CheckUsername(this, $(this).val());
	})

	$("#Iota_Login_Username").on("change", function() {
		CheckUsername(this,$(this).val());
	})

	$("#Iota_Login_Password").on("input", function() {
		CheckPassword(this, $(this).val());
	})

	$("#Iota_Login_Password").on("change", function() {
		CheckPassword(this,$(this).val());
	})

	$("form").submit(function (e) {
		if(!($(".Invalid").length == 0 && $(".Valid").length == 2)) {
			e.preventDefault();
			alert("Holy shit you have so much wrong");
		}
	});

});