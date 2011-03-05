// When the thumbnail is clicked, make it the active one and resize the image to the larger size

$(document).ready(function(){
	
	// Get rid of the HTML defaults for those without javascript
	$('section.hproduct div#photos ul').removeClass('no-javascript');
	$('section.hproduct div#photos div.current-photo').remove();
	
	// Choose the default selected photo
    $('section.hproduct div#photos ul li:first').addClass('active-photo').clone().prependTo('section.hproduct div#photos');
	$('section.hproduct div#photos > li.active-photo').removeClass('active-photo').addClass('current-photo');
	$('section.hproduct div#photos > li.current-photo a img').attr('height', '340');
	$('section.hproduct div#photos > li.current-photo a img').attr('width', '340');
	$('section.hproduct div#photos > li.current-photo').wrap('<div class="current-photo" />');
	$('section.hproduct div#photos div.current-photo li.current-photo img').clone().appendTo('section.hproduct div#photos div.current-photo');
	$('section.hproduct div#photos div.current-photo li.current-photo').remove();
    
    $('section.hproduct div#photos ul li').click(function() {
 		// Remove the current selected photo
 		$('section.hproduct div#photos div.current-photo').remove();
 		$('section.hproduct div#photos ul li.active-photo').removeClass("active-photo");
 		// Make the clicked photo the current selected photo
		$(this).toggleClass("active-photo");
		$('section.hproduct div#photos ul li.active-photo').clone().prependTo('section.hproduct div#photos');
		$('section.hproduct div#photos > li.active-photo').removeClass('active-photo').addClass('current-photo');
		$('section.hproduct div#photos > li.current-photo a img').attr('height', '340');
		$('section.hproduct div#photos > li.current-photo a img').attr('width', '340');
		$('section.hproduct div#photos > li.current-photo').wrap('<div class="current-photo" />');
		$('section.hproduct div#photos div.current-photo li.current-photo img').clone().appendTo('section.hproduct div#photos div.current-photo');
		$('section.hproduct div#photos div.current-photo li.current-photo').remove();
 		return false; //Prevent the browser jump to the link anchor
	});
	
});