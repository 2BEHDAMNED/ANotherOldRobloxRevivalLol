// SURELY THERE'S A BETTER WAY OF DOING THIS

if(typeof(ANORRL) == "undefined") {
	ANORRL = {}
}

ANORRL.Register = {
	pattern: /^[a-zA-Z0-9]{3,20}$/,
	
	IsUsernameValid: function(input) {
		return input.trim().length != 0 && ANORRL.Register.pattern.test(input);
	},

	CheckUsername: function(element, input) {
		if(input.length != 0) {
			if(ANORRL.Register.IsUsernameValid(input)) {
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
	CheckMainPassword: function(element, input) {

		var confirmPasswordElement = $("#ANORRL_Signup_ConfirmPassword");
	
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
	
			if(input != confirmPasswordElement.val()) {
				$("#v_confirmpassword").html("Passwords do not match!");
				confirmPasswordElement.addClass("Invalid");
				confirmPasswordElement.removeClass("Valid");
			} else {
				$("#v_confirmpassword").html("");
				confirmPasswordElement.removeClass("Invalid");
				confirmPasswordElement.addClass("Valid");
			}
		} else {
			$("#v_password").html("");
			confirmPasswordElement.removeClass("Invalid");
			confirmPasswordElement.removeClass("Valid");
			$(element).removeClass("Valid");
			$(element).removeClass("Invalid");
		}
	},

	CheckSecondPassword: function(element, input) {
		if(input.length != 0) {
			if(input == $("#ANORRL_Signup_Password").val()) {
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
	},
	CheckAccessKey: function(element, input) {
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
}

$(function(){
	$("#ANORRL_Signup_Username").on("input change", function() {
		ANORRL.Register.CheckUsername(this, $(this).val());
	})

	$("#ANORRL_Signup_Password").on("input change", function() {
		ANORRL.Register.CheckMainPassword(this, $(this).val());
	})

	$("#ANORRL_Signup_ConfirmPassword").on("input change", function() {
		ANORRL.Register.CheckSecondPassword(this, $(this).val());
	})

	$("#ANORRL_Signup_AccessKey").on("input change", function() {
		ANORRL.Register.CheckAccessKey(this, $(this).val());
	})

	$("form").submit(function (e) {
		// Basically, IE literally doesn't want to check if anything has been changed to an input unless directly by keys
		// This just runs all the checks before submission.
		ANORRL.Login.CheckUsername(document.getElementById("ANORRL_Signup_Username"), $("#ANORRL_Signup_Username").val());
		ANORRL.Login.CheckMainPassword(document.getElementById("ANORRL_Signup_Password"), $("#ANORRL_Signup_Password").val());
		ANORRL.Login.CheckSecondPassword(document.getElementById("ANORRL_Signup_ConfirmPassword"), $("#ANORRL_Signup_ConfirmPassword").val());
		ANORRL.Login.CheckAccessKey(document.getElementById("ANORRL_Signup_AccessKey"), $("#ANORRL_Signup_AccessKey").val());
		
		if(!($(".Invalid").length == 0 && $(".Valid").length == 4)) {
			e.preventDefault();
			alert("Holy shit you have so much wrong");
		}
	});

});