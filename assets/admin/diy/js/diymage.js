;
// Used in the control section to reset the value to the origin value
function resetValue(id, value) {
    $j("#" + id).val(value);
}


function resetColour(id, value) {
    if (value != "") {
        $j.fn.mColorPicker.setInputColor(id, "#" + value);
    } else {
        $j.fn.mColorPicker.setInputColor(id, "");
    }
}

// Reset all values in group
function resetValues(id) {
    // Handle inputs
    $j("#" + id + " input, " + 
         "#" + id + " select").each(function() {
        // If colour
        if (jQuery(this).hasClass("colourpicker")) {
            resetColour(this.id, this.getAttribute('data-default'));
        } else if (jQuery(this).hasClass("delete")) {
            // If image checkbox
            jQuery(this).attr("checked", true);
        } else {
            resetValue( this.id, this.getAttribute('data-default'));
            if (this.id == "layout") {
                // Also need to update the builder
                updateLayoutBuilder();
            }
        }
    });       
}

$j(function () {
	$j('#nav > li.parent > a > span').each(function (i, el) {
		el = $j(el);
		
		var label = el.html();
		
		if (label == "DIY Mage") {
			el.parent().parent().addClass('diymage');
		}
	});
});