(function ($, undefined) {
    $(document).ready(function(){

        //Hide (Collapse) the toggle containers on load
        $("div.block-layered-nav dl#narrow-by-list dd").hide(); 
        // Show the first container open
        $("div.block-layered-nav dl#narrow-by-list dd:eq(0)").show();
    	$("div.block-layered-nav dl#narrow-by-list dt:eq(0)").addClass("active");

        //Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
        $("div.block-layered-nav dl#narrow-by-list dt").click(function(){
            $(this).toggleClass("active").next().slideToggle("fast");
            return false; //Prevent the browser jump to the link anchor
        });

    });
})(jQuery);
