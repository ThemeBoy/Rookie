(function($) {
	// Trigger colors section.
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
	
	// Trigger layout section.
	$('body').on('click', '#accordion-section-rookie_layout', function() {
		$('#accordion-section-rookie_layout #customize-control-themeboy_content_width input').change();
	});
	// Update content width display
	$('body').on('change', '#accordion-section-rookie_layout #customize-control-themeboy_content_width input', function() {
		$el = $('#accordion-section-rookie_layout #customize-control-themeboy_content_width .customize-control-description');
		$el.css('float', 'right').find('span').html($(this).val()+'px').css('line-height', '24px');
	});
	$('body').on('mouseup', '#accordion-section-rookie_layout #customize-control-themeboy_content_width .customize-control-description a', function() {
		$input = $('#accordion-section-rookie_layout #customize-control-themeboy_content_width input');
		if ( '#minus' === $(this).attr('href') ) {
			$input.val(function( index, val) {
				console.log(val);
				return +val - 10;
			});
		} else if ( '#plus' === $(this).attr('href') ) {
			$input.val(function( index, val) {
				console.log(val);
				return +val + 10;
			});
		}
		return false;
	});
})(jQuery);