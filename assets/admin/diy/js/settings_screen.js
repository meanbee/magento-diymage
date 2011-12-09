document.observe('dom:loaded', function () {
	if ($$('#diy_license').length > 0) {
	    $$('html')[0].addClassName("diy_settings");
	}
});
