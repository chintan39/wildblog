/**
 * TinyMCE editor settings definition
 */

function tinymce_add_arrays(arr1, arr2) {
	var res = {};
	for (i in arr1) {
		res[i] = arr1[i];
	}
	for (i in arr2) {
		res[i] = arr2[i];
	}
	res['image_manager_path'] = res['document_base_url'] + res['image_manager_path_rel'];
	res['file_manager_path'] = res['document_base_url'] + res['file_manager_path_rel'];
	return res;
}

var tinymce_base = {
	// General options
	mode : "exact",
	theme : "advanced",

	content_css : "../../themes/Common/css/content.css",

	file_browser_callback : "MediaManager.filebrowserCallBack",

	image_manager_path_rel : "app/libs/ImageManager/",
	image_manager_path : "",		// must be overloaded
	image_manager_base_url : "../../../",
	image_manager_base_dir : "../../../",
	image_manager_dir : "",			// must be overloaded

	file_manager_path_rel : "app/libs/ImageManager/",
	file_manager_path : "",			// must be overloaded
	file_manager_base_url : "../../../",
	file_manager_base_dir : "../../../",
	file_manager_dir : "",			// must be overloaded

	external_link_list_url : "site-links/",

	convert_urls : false,
	document_base_url : "",		// must be overloaded
	entity_encoding : "raw",

	theme_advanced_toolbar_location : "bottom",
	theme_advanced_toolbar_align : "center",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : false

}

var tinymce_full = tinymce_add_arrays(tinymce_base, {
	// Theme options
	plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,insertfile,insertthumb",
	theme_advanced_buttons1 : "undo,redo,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image,insertfile,insertthumb,cleanup,code,|,forecolor,backcolor,|,fullscreen,preview,help",
	theme_advanced_buttons3 : "tablecontrols,|,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak"
});


var tinymce_bbcode = tinymce_add_arrays(tinymce_base, {
	plugins : "bbcode",
	theme_advanced_buttons1 : "bold,italic,underline,undo,redo,link,unlink,forecolor,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_styles : "Code=codeStyle;Quote=quoteStyle",
	content_css : "css/bbcode.css",
	entity_encoding : "raw",
	add_unload_trigger : false,
	remove_linebreaks : false,
	inline_styles : false,
	convert_fonts_to_spans : false
});

var tinymce_lite = tinymce_add_arrays(tinymce_base, {
	plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,preview,media,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,insertfile,insertthumb",
	theme_advanced_buttons1 : "undo,redo,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,blockquote,|,link,unlink,anchor,image,insertfile,insertthumb,emotions,cleanup,code,|,forecolor,backcolor,|,fullscreen,preview,help",
	theme_advanced_buttons3 : "tablecontrols,|,removeformat,|,sub,sup,|,charmap,iespell,media,advhr,styleprops",
	theme_advanced_buttons4 : ""
});

var tinymce_micro = tinymce_add_arrays(tinymce_base, {
	plugins : "",
	theme_advanced_buttons1 : "bold,italic,underline,undo,redo,forecolor,fontsizeselect,fontselect,bullist,numlist,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	width : "470",
	height : "250"
});


