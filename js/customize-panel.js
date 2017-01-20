(function($) {
	// Trigger colors section.
	$('body').on('click', '[id$="accordion-section-colors"]', function() {
		$('[id$="accordion-section-colors"] #customize-control-themeboy_customize').change();
	});
	// Hide advanced custom colors when option is off.
	$('body').on('change', '[id$="accordion-section-colors"] #customize-control-themeboy_customize', function() {
		$el = $('[id$="accordion-section-colors"] #customize-control-themeboy_primary, [id$="accordion-section-colors"] #customize-control-themeboy_background, [id$="accordion-section-colors"] #customize-control-themeboy_text, [id$="accordion-section-colors"] #customize-control-themeboy_heading, [id$="accordion-section-colors"] #customize-control-themeboy_link');
		if ( $(this).find('input').prop('checked') ) {
			$el.show();
		} else {
			$el.hide();
		}
	});
	
	// Trigger layout section.
	$('body').on('click', '[id$="accordion-section-rookie_layout"]', function() {
		$('[id$="accordion-section-rookie_layout"] #customize-control-themeboy_content_width input').change();
	});
	// Update content width display
	$('body').on('change', '[id$="accordion-section-rookie_layout"] #customize-control-themeboy_content_width input', function() {
		$el = $('[id$="accordion-section-rookie_layout"] #customize-control-themeboy_content_width .customize-control-description');
		$el.css('float', 'right').find('span').html($(this).val()+'px').css('line-height', '24px');
	});
	$('body').on('mouseup', '[id$="accordion-section-rookie_layout"] #customize-control-themeboy_content_width .customize-control-description a', function() {
		$input = $('[id$="accordion-section-rookie_layout"] #customize-control-themeboy_content_width input');
		console.log($input);
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