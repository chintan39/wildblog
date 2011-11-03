/**
 * $Id: editor_plugin_src.js 677 2008-03-07 13:52:41Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.InsertThumbPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceInsThumb', function() {
				// Internal image object like a flash placeholder
				if (ed.dom.getAttrib(ed.selection.getNode(), 'class').indexOf('mceItem') != -1)
					return;

				ed.windowManager.open({
					file : url + '/insertthumb.htm',
					width : 480 + parseInt(ed.getLang('insertthumb.delta_width', 0)),
					height : 385 + parseInt(ed.getLang('insertthumb.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('insertthumb', {
				title : 'insertthumb.insertthumb_desc',
				cmd : 'mceInsThumb'
			});
		},

		getInfo : function() {
			return {
				longname : 'Insert File',
				author : 'WildBlog',
				authorurl : 'http://code.google.com/p/wildblog/',
				infourl : 'http://code.google.com/p/wildblog/',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('insertthumb', tinymce.plugins.InsertThumbPlugin);
})();