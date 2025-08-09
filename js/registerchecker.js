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

function CheckMainPassword(element, input) {
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

		if(input != $("#Iota_Signup_ConfirmPassword").val()) {
			$("#v_confirmpassword").html("Passwords do not match!");
			$("#Iota_Signup_ConfirmPassword").addClass("Invalid");
			$("#Iota_Signup_ConfirmPassword").removeClass("Valid");
		} else {
			$("#v_confirmpassword").html("");
			$("#Iota_Signup_ConfirmPassword").removeClass("Invalid");
			$("#Iota_Signup_ConfirmPassword").addClass("Valid");
		}
	} else {
		$("#v_password").html("");
		$("#Iota_Signup_ConfirmPassword").removeClass("Invalid");
		$("#Iota_Signup_ConfirmPassword").removeClass("Valid");
		$(element).removeClass("Valid");
		$(element).removeClass("Invalid");
	}
}

function CheckSecondPassword(element, input) {
	if(input.length != 0) {
		if(input == $("#Iota_Signup_Password").val()) {
			$("#v_confirmpassword").html("");
			$(element).removeClass("Invalid");
			$(element).addClass("Valid");
		} else {
			$("#v_confirmpassword").html("Passwords do not match!");
			$(element).addClass("Invalid");
			$(element).removeClass("Valid");
		}
	} else {
		$("#v_confirmpassword").html("");
		$(element).removeClass("Valid");
		$(element).removeClass("Invalid");
	}
}

function CheckAccessKey(element, input) {
	if(input.length != 0) {
		if(input.length == 36) {
			$("#v_access").html("");
			$(element).removeClass("Invalid");
			$(element).addClass("Valid");
		} else {
			$("#v_access").html("Invalid access key.");
			$(element).addClass("Invalid");
			$(element).removeClass("Valid");
		}
	} else {
		$("#v_access").html("");
		$(element).removeClass("Valid");
		$(element).removeClass("Invalid");
	}
}

$(function(){
	$("#Iota_Signup_Username").on("input", function() {
		CheckUsername(this, $(this).val());
	})

	$("#Iota_Signup_Username").on("change", function() {
		CheckUsername(this,$(this).val());
	})

	$("#Iota_Signup_Password").on("input", function() {
		CheckMainPassword(this, $(this).val());
	})

	$("#Iota_Signup_Password").on("change", function() {
		CheckMainPassword(this,$(this).val());
	})

	$("#Iota_Signup_ConfirmPassword").on("input", function() {
		CheckSecondPassword(this, $(this).val());
	})

	$("#Iota_Signup_ConfirmPassword").on("change", function() {
		CheckSecondPassword(this,$(this).val());
	})

	$("#Iota_Signup_AccessKey").on("input", function() {
		CheckAccessKey(this, $(this).val());
	})

	$("#Iota_Signup_AccessKey").on("change", function() {
		CheckAccessKey(this,$(this).val());
	})

	$("form").submit(function (e) {
		if(!($(".Invalid").length == 0 && $(".Valid").length == 4)) {
			e.preventDefault();
			alert("Holy shit you have so much wrong");
		}
	});

});