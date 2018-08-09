(function($) {
	$('.mega-slider').each(function() {
		// Define show and hide label triggers
		$(this)
			.find('.mega-slider__slide')
			.on('showLabel', function(event) {
				$(this)
					.find('.mega-slider__slide__label')
					.css('bottom', '-50px')
					.animate({
						bottom: 0
					}, 400);
			}).on('hideLabel', function(event) {
				$(this)
					.find('.mega-slider__slide__label')
					.css('bottom', 0)
					.animate({
						bottom: '-50px'
					}, 200);
			});

		// Define this slide trigger
		$(this)
			.find('.mega-slider__row')
			.on('thisSlide', function(event) {
				if($(event.target).hasClass('mega-slider__row__link') || $(this).hasClass('mega-slider__row--active')) return;
				slide = $(this).index();
				$(this)
					.addClass('mega-slider__row--active')
					.siblings()
					.removeClass('mega-slider__row--active');
				$(this)
					.closest('.mega-slider')
					.find('.mega-slider__slide--active')
					.trigger('hideLabel')
					.closest('.mega-slider__slide')
					.siblings()
					.andSelf()
					.eq(slide)
					.stop(true, true)
					.css('pointer-events', '')
					.css('z-index', 0)
					.css('display', 'block')
					.addClass('mega-slider__slide--active')
					.siblings('.mega-slider__slide--active')
					.css('pointer-events', 'none')
					.css('z-index', '')
					.removeClass('mega-slider__slide--active')
					.fadeOut(400, function() {
						$(this)
							.siblings()
							.andSelf()
							.eq(slide)
							.trigger('showLabel');
					});
			});

		// Activate click trigger
		$(this)
			.find('.mega-slider__row')
			.on('click', function(event) {
				$(this).trigger('thisSlide');
				$(this)
					.closest('.mega-slider')
					.trigger('resetInterval');
			});

		// Define next slide trigger
		$(this)
			.on('nextSlide', function(event) {
				$row = $(this).find('.mega-slider__row--active').next();
				if ( 0 == $row.length ) {
					$row = $(this).find('.mega-slider__row');
				}
				$row.eq(0).trigger('thisSlide');
			});

		// Animate first label
		$(this)
			.closest('.mega-slider')
			.find('.mega-slider__slide--active .mega-slider__slide__label')
			.animate({
				bottom: 0
			}, 400);

		// Define start, stop, and reset interval triggers
		$(this)
			.on('startInterval', function(event) {
				if ( '1' == $(this).data('autoplay') ) {
					var $slider = $(this);
					$slider.data('interval', setInterval(function () {
						$slider.trigger('nextSlide');
					}, parseInt( $slider.data('delay') ) * 1000 ) );
				}
			}).on('stopInterval', function(event) {
				clearInterval( $(this).data('interval') );
			}).on('resetInterval', function(event) {
				$(this).trigger('stopInterval').trigger('startInterval');
			});

		// Activate autoplay trigger
		$(this).trigger('startInterval');

	});
})(jQuery);