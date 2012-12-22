$(document).ready(function(){
 
	$(".kirjaudu").click(function(){
		$("#slide-panel_r").fadeOut("slow");
		$("#slide-panel_k").fadeIn("slow");
	});
	
	$(".rekisteroidy").click(function(){
		$("#slide-panel_k").fadeOut("slow");
		$("#slide-panel_r").fadeIn("slow");
	});

	$(".sulje").click(function(){
		$("#slide-panel_r").fadeOut("slow");
		$("#slide-panel_k").fadeOut("slow");
	});

});
