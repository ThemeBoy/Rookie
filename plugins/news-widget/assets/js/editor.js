/* global tinymce */
( function () {
	tinymce.PluginManager.add( 'news_widget_button', function( editor, url ) {
		var ed = tinymce.activeEditor;
		editor.addButton( 'news_widget_button', {
			title: ed.getLang( 'strings.insert_news_widget' ),
			text: false,
			icon: 'news-widget',
			onclick: function() {
				// triggers the thickbox
				var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				W = W - 80;
				H = H - 84;
				tb_show( ed.getLang( 'strings.insert_news_widget' ), 'admin-ajax.php?action=news_widget&width=' + W + '&height=' + H );
			}
		});
	});
})();
