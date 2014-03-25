var chartpad = chartpad || {};

chartpad.home = (function () {
	"use strict";

	var currentItem = 1,
		timerPointer,
		load,
		switchLoop,
		changeItem,
		resetItems,
		forgotPassReturn,
		signupReturn,
		loginFormReturn,
		loginMessage,
		signupReturn;

	load = function () {
		$(".content").css("margin-top", ($(window).height() - 451) / 2).css("opacity", 1);
		$("body").css("background-image", "url('../images/bg.png')");

		$(".option").bind("click", function(ev) {
			ev.stopPropagation();
			var id = parseInt(new String(ev.target.id).split("_")[1]);
			clearTimeout(timerPointer);
			changeItem(id);
		});

		document.querySelector(".option").addEventListener("mouseout", function (ev) {
			var relTag = ev.relatedTarget || ev.toElement;
			if (relTag.nodeName === "HTML" || relTag.nodeName === "BODY") {
			 	clearTimeout(timerPointer);
			 	timerPointer = setTimeout(switchLoop, 5000);
			}
		}, false);

		document.querySelector(".option").addEventListener("mouseover", function (ev) {
			var relTag = ev.relatedTarget || ev.fromElement;
			if (relTag.nodeName === "HTML" || relTag.nodeName === "BODY") {
			 	clearTimeout(timerPointer);
			}
		}, false);

		$("#forgot-pass form").bind("submit", function (ev) {
			ev.preventDefault();
			$.ajax({
				type: "POST",
				url: "forgotpass.php",
				data: $("#forgot-pass > form").serialize(),
				success: forgotPassReturn,
				dataType: "text"
			});
			return false;
		});
		
		$("#sign-up form").bind("submit", function (ev) {
			ev.preventDefault();
			$.ajax({
				type: "POST",
				url: "signupform.php",
				data: $("#sign-up > form").serialize(),
				success: signupReturn,
				dateType: "text"
			});
			return false;
		});
		
		$("#login-form form").bind("submit", function (ev) {
			ev.preventDefault();
			$.ajax({
				type: "POST",
				url: "site/login.php",
				data: $("#login-form > form").serialize(),
				success: loginFormReturn,
				dateType: "text"
			});
			return false;
		});
		
		$(window).bind("resize", function (ev) {
			$(".content").css("margin-top", ($(window).height() - 451) / 2).css("opacity", 1);
		});

		//share boxes
		$("#shareToggle").bind("click", function (ev) {
			if(parseInt($(".shareBox").css("right"), 10) < 0) {
				$(".shareBox").css("right", "25px");
			} else {
				$(".shareBox").css("right", "-999px");
			}
		});

		//add share buttons functionality
		$("#shareFacebook").bind("click", function () {
			window.open("http://www.facebook.com/sharer.php?u="+encodeURIComponent(location), 'Share on Facebook','width=600,height=460,menubar=no,location=no,status=no');
		});

		$("#shareTwitter").bind("click", function () {
			window.open("http://www.twitter.com/intent/tweet/?url="+encodeURIComponent(location)+"&text=Found this pretty nifty website, check it out ", 'Share on Twitter','width=600,height=460,menubar=no,location=no,status=no');
		});

		$("#shareGooglePlus").bind("click", function () {
			window.open("https://plus.google.com/share?url="+encodeURIComponent(location), 'Share on Google+','width=600,height=460,menubar=no,location=no,status=no');
		});

		$("#shareLinkedIn").bind("click", function () {
			window.open("http://www.linkedin.com/shareArticle?mini=true&url="+encodeURIComponent(location), 'Share on LinkedIn','width=600,height=460,menubar=no,location=no,status=no');
		});
		
		//lightboxes
		$('#message-click').click(function(e) {
			$("#message").lightbox_me({centered: true, closeEsc: true, appearEffect: "fadeIn", onLoad: function() {
				$("#message").find("input:first").focus();
			}});
			e.preventDefault();
		});

		$('#contact-btn').click(function(e) {
			$("#contact").lightbox_me({centered: true, closeEsc: true, appearEffect: "fadeIn", onLoad: function() {
				$("#contact").find("input:first").focus();
			}});
			e.preventDefault();
		});

		$('#faq-btn').click(function(e) {
			$("#faq").lightbox_me({centered: true, closeEsc: true, appearEffect: "fadeIn", onLoad: function() {
				$("#faq").find("input:first").focus();
			}});
			e.preventDefault();
		});

		$('#forgot-pass-btn').click(function(e) {
			$("#forgot-pass").lightbox_me({centered: true, closeEsc: true, appearEffect: "fadeIn", onLoad: function() {
				$("#forgot-pass").find("input:first").focus();
			}});
			e.preventDefault();
		});

		$('#get-started').click(function(e) {
			$("#sign-up").lightbox_me({centered: true, closeEsc: true, appearEffect: "fadeIn", onLoad: function() {
				$("#sign-up").find("input:first").focus();
			}});
			e.preventDefault();
		});

		//Start home page animation
		switchLoop();
	};

	forgotPassReturn = function (data) {
		$("#forgot-pass-output").html(data);
	};
	
	signupReturn = function (data) {
		var xData = JSON.parse(data);
		switch(xData.status) {
			case 0:
				//Color Green
				$("#sign-up-output").css("background-color", "#57e964").text(xData.message);
				break;
			case 1:
				//Color Red
				$("#sign-up-output").css("background-color", "#ffa6a3").text(xData.message);
				break;
			default:
				//Unexpected error
				$("#sign-up-output").css("background-color", "#57e964").text("An unexpected error has occured");
		}
	};
	
	loginFormReturn = function (data) {
		var xData = JSON.parse(data);
		switch(xData.status) {
			case 1:
				$("#loginMessage").text(xData.message);
				break;
			case 2:
				document.location = xData.message;
				break;
			default:
				$("#loginMessage").text("An unexpected error has occured");
		}
	};

	switchLoop = function () {
		if(currentItem == 8)
			currentItem = 1;
		changeItem(currentItem);
		clearTimeout();
		timerPointer = setTimeout(switchLoop, 5000);
	};

	changeItem = function (id) {
		currentItem = id + 1;
		resetItems();
		$("#main_" + id).css("opacity", "1");
		$("#option_" + id).addClass("selected");
	};

	resetItems = function () {
		$(".main > span").css("opacity", 0);
		$(".option > li").removeClass("selected");
	};

	return {
		"load": load
	}
}());

$(document).ready(chartpad.home.load);