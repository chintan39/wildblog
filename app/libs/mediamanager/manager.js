/*
Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Functions for the ImageManager, used by manager.php only	
 * @author $Author: Wei Zhuo $
 * @version $Id: manager.js 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 * TODO: move to base package - necessary?
 * TODO: directory listing dropbox(selectbox)
 * TODO: jumploader - not, instead: http://jupload.sourceforge.net/description.html
 */
	
// thumbs directory
var IM_THUMB_DIR = '.thumbs';
 
//initialise the form
init = function () 
{
	__dlg_init();

	var uploadForm = document.getElementById('uploadForm');
	if(uploadForm) uploadForm.target = 'imgManager';

	var param = window.mediaDialogArguments;

	var url = '';
	
	if (param && param["f_url"]) 
	{
		url = param["f_url"];
	}
	
	if (opener.MediaDialog._hasTmp()) {
		url = opener.MediaDialog._getTmp();
	}
	
	var reg = '^(media\/)' + IM_THUMB_DIR + '\/(.*\/)?(\\d+)x(\\d+)([berc])_thumb_([^\/]+)$';
	var m = url.match(reg);
	if (m) {
		// we use the thumbnail
		setImageThumb(m[1] + (m[2] ? m[2] : ''), m[3], m[4], m[5], m[6]);
	} else {
		// we do not use the thumbnail
		$('f_url').value = url;
	}
		
	$('f_url').focus();
}


function onCancel() 
{
	opener.MediaDialog._clearTmp();
	__dlg_close(null);
	return false;
}

/* 
 * Retrieving the thumbnail path when selecting image is done
 * @return WIDTHxHEIGHTm or false, where m is mod
 */
function getImageThumb() {
	var x = $('f_size');
	if (x && x.value) {
		return x.value + 'r';
	} else {
		return false;
	}
}

// initing the form following the thumb properties
function setImageThumb(dir, width, height, mode, fileName) {
	$('f_url').value = dir + fileName;
	$('f_size').value = width + 'x' + height;
}

function onOK() 
{
	// pass data back to the calling window
	var param = new Object();
	var id = "f_url";
	var el = document.getElementById(id);
	if (el.value.length > 0) {
		var thumb = getImageThumb();
		var fileName = el.value;
		if (thumb.length > 0) {
			var reg = new RegExp('^(media\/)(.*\/)?([^\/]*)$');
			fileName = fileName.replace(reg, "$1" + IM_THUMB_DIR + "/$2" + thumb + "_thumb_$3");
		}
		param[id] = fileName;
		param[id + '__original'] = el.value;
	} else {
		param[id] = "";
		param[id + '__original'] = "";
	}
		
	opener.MediaDialog._clearTmp();
	
	__dlg_close(param);
	return false;
}

function onSelectItem(img, elem)
{
	var el = document.getElementById("f_url");
	el.value = img;
	highlightItem(elem);
	
	opener.MediaDialog._setTmp(img);
	
	return false;
}

function highlightItem(actualItem) {
	$$('#mediaManagerItems a.item').each(function(item) {
		if (item == actualItem) {
			item.addClassName('active');
		} else {
			item.removeClassName('active');
		}
	});
}


function addEvent(obj, evType, fn)
{ 
	if (obj.addEventListener) { obj.addEventListener(evType, fn, true); return true; } 
	else if (obj.attachEvent) {  var r = obj.attachEvent("on"+evType, fn);  return r;  } 
	else {  return false; } 
} 



//addEvent(window, 'load', init);
