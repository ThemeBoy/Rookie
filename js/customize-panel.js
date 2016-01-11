(function($) {
	$('body').on('click', '#accordion-section-colors', function() {
		$('#accordion-section-colors #customize-control-themeboy_customize').change();
	});
	// Hide advanced custom colors when option is off.
	$('body').on('change', '#accordion-section-colors #customize-control-themeboy_customize', function() {
		$el = $('#accordion-section-colors #customize-control-themeboy_primary, #accordion-section-colors #customize-control-themeboy_background, #accordion-section-colors #customize-control-themeboy_text, #accordion-section-colors #customize-control-themeboy_heading, #accordion-section-colors #customize-control-themeboy_link');
		if ( $(this).find('input').prop('checked') ) {
			$el.show();
		} else {
			$el.hide();
		}
	});
})(jQuery);