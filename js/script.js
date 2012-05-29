/* Author: Codeworks */

$(document).ready(function() {
	var windowHeight = $(window).height();
	$("div.container").css({ 'margin-top': (windowHeight/2)/4 });
	
	$("#content").children(":not(hr)").css("padding", "5px 20px");
	
	$("form").submit(function() {
		$.noop();
	});
	
	$("#btn-search").click(function() {
		if($("form:not(#ajax-form)").validate().form())
			$("form:not(#ajax-form)").trigger('submit');
	});
	
	$("#btn-submit").click(function() {
		if($("form:not(#ajax-form)").validate().form()) {
			$("i.icon-ok").removeClass("icon-ok").addClass("icon-refresh");
			var i=0;
			var timeout = self.setInterval(function() {
				i+=3;
				$(".icon-refresh").css({
					"-webkit-transform": "rotate("+i+"deg)"
				});
			}, 10);
			$.post('submit-index.php', $("form").serialize(), function(data) {
				$("i.icon-refresh").removeClass("icon-refresh").addClass("icon-ok");
				timeout = window.clearInterval(timeout);
				$(".icon-ok").css({
					"-webkit-transform": "rotate(0deg)"
				});
				//alert(data);
				if(data=='not-found')
					window.location = "search.php?artist="+escape($("input[name=artist]").val())+"&fs=0";
				if(data=='OK') {
					fbExec.ifLoginStatus({
						loggedIn: function() {
							FB.api('/me/notifico:subscribe', 'post', { artist: "http://codeworks-eng.com/n.otifi.co/search.php?"+encodeURI($("form").serialize())+"&fs=1" });
						},
						loggedOut: function() {
							console.log("loggedout");
						},
						notAuthorized: function() {
							console.log("notAuth");
						}
					});
					var alert = '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><p>Subscription successfull!</p></div>';
					$("#subscription-results .content-container").append($(alert));
					$("hr.hidden").css("visibility", "visible");
					$("hr.hidden, #subscription-results").css("display", "block");
				}
				if(data=='already-subscribed') {
					var alert = '<div class="alert alert-info"><a class="close" data-dismiss="alert">×</a><p>You are already subscribed to this artist.</p></div>';
					$("#subscription-results .content-container").append($(alert));
					$("hr.hidden").css("visibility", "visible");
					$("hr.hidden, #subscription-results").css("display", "block");
				}
				if(data=='KO') {
					var alert = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><p>An error occurred..<br />Please, try again later</p></div>';
					$("#subscription-results .content-container").append($(alert));
					$("hr.hidden").css("visibility", "visible");
					$("hr.hidden, #subscription-results").css("display", "block");
				}
			});
		}
	});
	
	$("#ajax-subscribe").click(function() {
		if($("#ajax-form input#ajax-email").val()) {
			$("i.icon-ok").removeClass("icon-ok").addClass("icon-refresh");
			var i=0;
			var timeout = self.setInterval(function() {
				i+=3;
				$(".icon-refresh").css({
					"-webkit-transform": "rotate("+i+"deg)"
				});
			}, 10);
			$.post('submit-index.php', $("form").serialize(), function(data) {
				$("i.icon-refresh").removeClass("icon-refresh").addClass("icon-ok");
				timeout = window.clearInterval(timeout);
				$(".icon-ok").css({
					"-webkit-transform": "rotate(0deg)"
				});
				//alert(data);
				if(data=='not-found')
					window.location = "search.php?artist="+escape($("input[name=artist]").val())+"&fs=0";
				if(data=='OK') {
					fbExec.ifLoginStatus({
						loggedIn: function() {
							FB.api('/me/notifico:subscribe', 'post', { artist: window.location.href });
						},
						loggedOut: function() {
							console.log("loggedout");
						},
						notAuthorized: function() {
							console.log("notAuth");
						}
					});
					var alert = '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><p>Subscription successfull!</p></div>';
					$("#ajax-result").append($(alert));
				}
				if(data=='already-subscribed') {
					var alert = '<div class="alert alert-info"><a class="close" data-dismiss="alert">×</a><p>You are already subscribed to this artist.</p></div>';
					$("#ajax-result").append($(alert));
				}
				if(data=='KO') {
					var alert = '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><p>An error occurred..<br />Please, try again later</p></div>';
					$("#ajax-result").append($(alert));
				}
			});
		} else {
			$("#nomail-modal").modal({
				keyboard: true,
				show: true
			});
		}
	});
	
	$("a#modal-submit").live('click', function() {
		$("#modal-form").validate();
		if($("input#modal-email").valid()) {
			$("#ajax-form input#ajax-email").val($("input#modal-email").val());
			$("#nomail-modal").modal('hide');
			var as_email = '<span class="as-email">as '+$("#ajax-email").val()+'</span>';
			$("#ajax-subscribe").append($(as_email));
		}
	});
	
	$("input#modal-email").keypress(function(event) {
		if(event.which == 13) {
			event.preventDefault();
		}
	});
	
	$("a.close").live('click', function() {
		var e = $(this);
		var alert = $(this).parent();
		$(e).parent().parent().parent().parent().css("display", "none");
		$(e).parent().html("");
		$(alert).append($(e));
	});
});

window.fbAsyncInit = function() {
		FB.init({
			appId      : '387585684626143', // App ID
    	channelUrl : 'http://codeworks-eng.com/n.otifi.co/channel.php', // Channel File
    	status     : true, // check login status
    	cookie     : true, // enable cookies to allow the server to access the session
    	xfbml      : true  // parse XFBML
		});
		
		$("#fb-login-button").hide();
		$("#fb-like-box").show();
		
		fbExec.init();
		
		function manageLogin(response) {
			if(response.authResponse) {
				FB.api('/me', function(me) {
					$.post('submit-index.php', { 'email': me.email }, function(data) {
						//alert(data);
						if($(".as-email").lenght == 0) {
							var as_email = '<span class="as-email">'+me.email+'</span>';
							$("#ajax-subscribe").append($(as_email));
							$("#ajax-form input#ajax-email").val(me.email);
						} else {
							$('.as-email').text('as ' + me.email);
						}
					});
				});
			} else {
				$("#fb-login-button").show();
				$("#fb-like-box").hide();
			}
		}
		
		function manageStatusChange(response) {
			if(!response.authResponse) {
				$("#fb-login-button").show();
				$("#fb-like-box").hide();
			} else {
				$("#fb-login-button").hide();
				$("#fb-like-box").show();
			}
		}

    // Additional initialization code here
    FB.Event.subscribe('auth.login', manageLogin);
    FB.Event.subscribe('auth.statusChange', manageStatusChange);
    
    /*
    $("#ajax-subscribe").click(function() {
    	FB.api('/me/notifico:subscribe', 'post', { artist: window.location.href });
    });
    */
};

// Load the SDK Asynchronously
(function(d){
  var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement('script'); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));



