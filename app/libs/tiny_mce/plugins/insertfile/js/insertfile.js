var InsertFileDialog = {
	init : function(ed) {
		document.getElementById('hrefbrowsercontainerfile').innerHTML = getBrowserHTML('hrefbrowser','href','file','insertfile');
	},

	update : function() {
		var ed = tinyMCEPopup.editor, h, f = document.forms[0], st = '', pos, ext, size;
		if (f.href.value) {
			output = '<div class="insertfile';
	
			if (f.size.value) {
				output += ' size' + f.size.value;
			}
	
			output += '">';
			
			pos = f.href.value.search(/\.(aiff|asp|au|avi|bat|bmp|css|doc|gif|html|img|inf|ini|iso|jpg|midi|mov|mp3|mp4|mpg|pdf|php|png|psd|rar|tiff|txt|url|wav|wmv|xml|zip)$/);
			if (pos != -1) {
				ext = f.href.value.substring(pos+1);
			} else {
				ext = 'unknown';
			}
			
			switch (f.size.value) {
			case 1:
			case 2:
				size = '16';
			default:
			case 3:
			case 4:
				size = '32';
			case 5:
				size = '64';
				break;
			}
	
			output += '<a href="' + f.href.value + '" class="size' + size + '">';

			output += '<img src="media/.ico/' + size + '/filetype_' + ext + '.png" alt="' + ext + '" />';

			output += '<span class="size' + size + '">' + f.title.value + '</span>';

			output += '</a>';
	
			output += '<div class="clear"></div>';
			
			output += '</div>';
	
			ed.execCommand("mceInsertContent", false, output);
		}
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.requireLangPack();
tinyMCEPopup.onInit.add(InsertFileDialog.init, InsertFileDialog);
