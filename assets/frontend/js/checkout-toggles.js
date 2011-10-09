(function ($, undefined) {
    $(document).ready(function(){
	
    	//Hide (Collapse) the toggle containers on load
    	$("ol#checkoutSteps li section").hide();
    	$("ol#checkoutSteps li section:first").show().addClass("active");

    	//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
    	$("ol#checkoutSteps li h2").click(function(){
    		$("ol#checkoutSteps li section.active").slideToggle("slow").removeClass("active");
    		$(this).next().toggleClass("active").slideToggle("slow");
    		return false; //Prevent the browser jump to the link anchor
    	});
	
    	//On selecting the continue button, jump to the next step
    	$("ol#checkoutSteps li button.continue").click(function(){
    		$("ol#checkoutSteps li section.active").slideToggle("slow").removeClass("active");
    		return false; //Prevent the browser jump to the link anchor
    	});
    });
})(jQuery);