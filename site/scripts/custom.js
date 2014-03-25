//Custom Javascript for alphablend page transition and dynamic page titles. Copyright M.Sharp 2012
$(document).ready(function() {
	
	document.title = document.getElementById("titles").innerHTML;
	
	$("article").css("display", "none");

    $("article").fadeIn(2000);
    
	$("a.cssroll").click(function(event){
		event.preventDefault();
		linkLocation = this.href;
		$("article").fadeOut(1000, redirectPage);		
	});
		
	function redirectPage() {
		window.location = linkLocation;
	}

});
