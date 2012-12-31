// Dialog v3.0 - Copyright (c) 2003-2004 interactivetools.com, inc.
// This copyright notice MUST stay intact for use (see license.txt).
//
// Portions (c) dynarch.com, 2003-2004
//
// A free WYSIWYG editor replacement for <textarea> fields.
// For full source code and docs, visit http://www.interactivetools.com/
//
// Version 3.0 developed by Mihai Bazon.
//   http://dynarch.com/mishoo
//
// $Id: dialog.js 26 2004-03-31 02:35:21Z Wei Zhuo $

// Though "Dialog" looks like an object, it isn't really an object.  Instead
// it's just namespace for protecting global symbols.

var IM_THUMB_DIR = '.thumbs';

function MediaDialog(url, action, init) {
	if (typeof init == "undefined") {
		init = window;	// pass this window object by default
	}
	MediaDialog._geckoOpenModal(url, action, init);
};

MediaDialog._parentEvent = function(ev) {
	setTimeout( function() { if (MediaDialog._modal && !MediaDialog._modal.closed) { MediaDialog._modal.focus() } }, 50);
	if (MediaDialog._modal && !MediaDialog._modal.closed) {
		MediaDialog._stopEvent(ev);
	}
};

// should be a function, the return handler of the currently opened dialog.
MediaDialog._return = null;

// constant, the currently opened dialog
MediaDialog._modal = null;

// the dialog will read it's args from this variable
MediaDialog._arguments = null;

MediaDialog._geckoOpenModal = function(url, action, init) {
	//var urlLink = "hadialog"+url.toString();
	var myURL = "hadialog"+url;
	var regObj = /\W/g;
	myURL = myURL.replace(regObj,'_');
	var left = (window.screenX + 750)/2-50; 
	var top = (window.screenY + 500)/2-50; 
	var dlg = window.open(url, myURL,
			      "toolbar=no,location=no,directories=no,menubar=no,personalbar=no,width=750,height=500," +
			      "scrollbars=no,resizable=yes,modal=yes,dependable=yes,left="+left+",top="+top);
	MediaDialog._modal = dlg;
	MediaDialog._arguments = init;

	// capture some window's events
	function capwin(w) {
		MediaDialog._addEvent(w, "click", MediaDialog._parentEvent);
		MediaDialog._addEvent(w, "mousedown", MediaDialog._parentEvent);
		MediaDialog._addEvent(w, "focus", MediaDialog._parentEvent);
	};
	// release the captured events
	function relwin(w) {
		MediaDialog._removeEvent(w, "click", MediaDialog._parentEvent);
		MediaDialog._removeEvent(w, "mousedown", MediaDialog._parentEvent);
		MediaDialog._removeEvent(w, "focus", MediaDialog._parentEvent);
	};
	capwin(window);
	// capture other frames
	for (var i = 0; i < window.frames.length; capwin(window.frames[i++]));
	// make up a function to be called when the MediaDialog ends.
	MediaDialog._return = function (val) {
		if (val && action) {
			action(val);
		}
		relwin(window);
		// capture other frames
		for (var i = 0; i < window.frames.length; relwin(window.frames[i++]));
		MediaDialog._modal = null;
	};
};


// event handling

MediaDialog._addEvent = function(el, evname, func) {
	if (MediaDialog.is_ie) {
		el.attachEvent("on" + evname, func);
	} else {
		el.addEventListener(evname, func, true);
	}
};


MediaDialog._removeEvent = function(el, evname, func) {
	if (MediaDialog.is_ie) {
		el.detachEvent("on" + evname, func);
	} else {
		el.removeEventListener(evname, func, true);
	}
};


MediaDialog._stopEvent = function(ev) {
	if (MediaDialog.is_ie) {
		ev.cancelBubble = true;
		ev.returnValue = false;
	} else {
		ev.preventDefault();
		ev.stopPropagation();
	}
};

MediaDialog._setTmp = function(val) {
	MediaDialog._tmpVal = val;
};

MediaDialog._getTmp = function() {
	return MediaDialog._tmpVal;
};

MediaDialog._hasTmp = function() {
	return (MediaDialog._tmpVal != null);
};

MediaDialog._clearTmp = function() {
	MediaDialog._tmpVal = null;
};

MediaDialog.agt = navigator.userAgent.toLowerCase();
MediaDialog.is_ie	   = ((MediaDialog.agt.indexOf("msie") != -1) && (MediaDialog.agt.indexOf("opera") == -1));

function selectMedia(image, type) {
	// TODO: implement window open using prototype windows
	var dir = '';
	if (image) {
		var reg = new RegExp('^media\/(.*)\/([^\/]*)$');
		var regThumb = new RegExp('^media\/' + IM_THUMB_DIR + '\/(.*\/)?([^\/]*)$');
		var regLastCut = new RegExp('[\/]$');
		if (image.value.match(regThumb)) {
			dir = image.value.replace(regThumb, "$1");
			if (dir.match(regLastCut)) {
				dir = dir.replace(regLastCut, "");
			}
		} else if (image.value.match(reg)) {
			dir = image.value.replace(reg, "$1");
		}
	}
    
	var outparam = {
		f_url    : (image ? image.value : null) 
	};
	
	MediaDialog('/admin/gallery/images/manager/?type=' + type + '&dir=' + dir, function (param) {
		if (!param)
			return false;
		
		if (image) {
			image.value = param.f_url;

			// if class of item contains addthumb, original value will be set 
			// to element with id [id]original
			if (image.className.search(/addthumb/) != -1) {
				if (image.original) 
					image.original.value = param.f_url__original;
			}
			
			image.focus();
			try { image.onchange();	} catch (e) { }
		}
		return false;
	}, outparam);
}

function MediaManager()
{
}

MediaManager.prototype.filebrowserCallBack = function(field_name, url, type, win)
{
	if (type == 'file') {
		var image = win.document.forms[0].elements[field_name];
		selectMedia(image, 'file');		
	} else {

		var image = win.document.forms[0].elements[field_name];
		var origEl = null;
		
		if (image.className.search(/addthumb/) != -1) {
			origEl = win.document.forms[0].elements[image.id + 'original'];
		}
		image.original = origEl

		selectMedia(image, 'image');
  }
}

var MediaManager = new MediaManager();

