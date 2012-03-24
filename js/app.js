$.mobile.pushStateEnabled = false;
$.mobile.ajaxEnabled = false;
$.mobile.defaultPageTransition = 'none';

$(document).ready(function(){
	window.setTimeout(function() {
	  $('div.alert').slideUp();
	}, 1000*10);
});


