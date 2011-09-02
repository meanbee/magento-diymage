(function ($, undefined) {
    $(document).ready(function(){
    	//Hide (Collapse) the toggle containers on load
    	$("ul#reviews-list li:not(:nth-child(-n+3))").hide();
    	$("form#write-review").hide();

    	//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
    	$("a.review-amount").click(function(){
    		$("ul#reviews-list li:not(:nth-child(-n+3))").toggleClass("active").slideToggle("slow");
    		$("ul#reviews-list li.view-all").toggleClass("active").slideToggle("slow");
    	});
    	$("ul#reviews-list li#view-all-reviews a").click(function(){
    		$("ul#reviews-list li:not(:nth-child(-n+3))").toggleClass("active").slideToggle("slow");
    		$("ul#reviews-list li#view-all-reviews").toggleClass("active").slideToggle("slow");
    	});
    	$("a.link-review").click(function(){
    		$("form#write-review").toggleClass("active").slideToggle("slow");
    	});
    });
})(jQuery);
