(function($) {
	$(".social-sidebar-settings tbody").sortable({
		items: ".social-sidebar-network",
		handle: "label",
		axis: "y"
	});

	$(".social-sidebar-network-link").bind("input", function() {
		if ( "" == $(this).val().trim() ) {
			$(this).closest(".social-sidebar-network").addClass("social-sidebar-network-inactive");
		} else {
			$(this).closest(".social-sidebar-network").removeClass("social-sidebar-network-inactive");
		}
	});
})(jQuery);