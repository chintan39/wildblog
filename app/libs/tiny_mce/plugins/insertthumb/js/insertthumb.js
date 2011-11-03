var InsertThumbDialog = {
	init : function(ed) {
		document.getElementById('hrefbrowsercontainerimage').innerHTML = getBrowserHTML('hrefbrowser','href','image','insertthumb');
		document.getElementById('hrefbrowsercontaineroriginal').innerHTML = getBrowserHTML('hrefbrowser','hreforiginal','image','insertthumb');
	},

	update : function() {
		var ed = tinyMCEPopup.editor, h, f = document.forms[0], st = '', pos, ext, size;
		if (f.href.value) {

			output = '<a rel="lightbox" href="' + f.hreforiginal.value + '" title="' + f.title.value + '">';
			
			output += '<img src="' + f.href.value + '" alt="' + f.title.value + '" />';

			output += '</a>';
	
			ed.execCommand("mceInsertContent", false, output);
		}
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.requireLangPack();
tinyMCEPopup.onInit.add(InsertThumbDialog.init, InsertThumbDialog);
