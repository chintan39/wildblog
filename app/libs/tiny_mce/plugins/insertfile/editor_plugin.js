/**
 * $Id: editor_plugin_src.js 677 2008-03-07 13:52:41Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.InsertFilePlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceInsFile', function() {
				// Internal image object like a flash placeholder
				if (ed.dom.getAttrib(ed.selection.getNode(), 'class').indexOf('mceItem') != -1)
					return;

				ed.windowManager.open({
					file : url + '/insertfile.htm',
					width : 480 + parseInt(ed.getLang('insertfile.delta_width', 0)),
					height : 385 + parseInt(ed.getLang('insertfile.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('insertfile', {
				title : 'insertfile.insertfile_desc',
				cmd : 'mceInsFile'
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
	tinymce.PluginManager.add('insertfile', tinymce.plugins.InsertFilePlugin);
})();