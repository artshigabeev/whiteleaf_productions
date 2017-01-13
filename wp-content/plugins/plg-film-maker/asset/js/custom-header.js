jQuery(document).ready(function ($) {
	/**
	 * Metabox custom jquery
	 */
	 //Check show Properties when click
	 $('.cmb_option').each(function() {
	 	var atCheck = $(this).attr('checked');
	  	var valShow = $(this).attr('value');
	  	if (atCheck =='checked') {
	  		checkHeader(valShow);
	  	};
	 });
	$('.cmb_option').change(function(event) {
		var headerShow = $(this).attr('id');
		checkHeader(headerShow);
	});

});

//Check header to show
function checkHeader(headerToshow){
	jQuery('.cmb_id__beautheme_header_using_cover, .cmb_id__beautheme_header_using_slider').hide();
	switch(headerToshow){
		case "_beautheme_your_custom_header2":
			jQuery('.cmb_id__beautheme_header_using_cover').show('slow');
		break;
		case "header_imagecover":
			jQuery('.cmb_id__beautheme_header_using_cover').show('slow');
		break;
		case "_beautheme_your_custom_header3":
			jQuery('.cmb_id__beautheme_header_using_slider').show('slow');
		break;
		case "header_slider":
			jQuery('.cmb_id__beautheme_header_using_slider').show('slow');
		break;
	}
}