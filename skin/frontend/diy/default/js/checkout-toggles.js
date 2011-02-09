$(document).ready(function(){
	
	//Hide (Collapse) the toggle containers on load
	$("ol#checkout-steps li section").hide();
	$("ol#checkout-steps li section:first").show().addClass("active");

	//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
	$("ol#checkout-steps li h2").click(function(){
		$("ol#checkout-steps li section.active").slideToggle("slow").removeClass("active");
		$(this).next().toggleClass("active").slideToggle("slow");
		return false; //Prevent the browser jump to the link anchor
	});
	
	//On selecting the continue button, jump to the next step
	$("ol#checkout-steps li button.continue").click(function(){
		$("ol#checkout-steps li section.active").slideToggle("slow").removeClass("active");
		return false; //Prevent the browser jump to the link anchor
	});

});