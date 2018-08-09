/* global tinymce */
( function () {
	tinymce.PluginManager.add( 'mega_slider_button', function( editor, url ) {
		var ed = tinymce.activeEditor;
		editor.addButton( 'mega_slider_button', {
			title: ed.getLang( 'strings.insert_mega_slider' ),
			text: false,
			icon: 'mega-slider',
			onclick: function() {
				// triggers the thickbox
				var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				W = W - 80;
				H = H - 84;
				tb_show( ed.getLang( 'strings.insert_mega_slider' ), 'admin-ajax.php?action=mega_slider&width=' + W + '&height=' + H );
			}
		});
	});
})();
