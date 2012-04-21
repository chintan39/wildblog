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
 * Base automatic functions to Wild-Web.eu
 * by Jan Horak (c) 2009
 *
 *--------------------------------------------------------------------------*/
 
Event.observe(window, 'load', function() { vanishEffect.init() });
Event.observe(window, 'load', function() { blank.init() });

/**
 * Default text in text inputs will clear after focus. 
 * This will be set automaticly after page is loaded.
 * The input, that should behave like this, should be signed with class vanish-onclick.
 */
vanishEffect = {
	/**
	 * Gets all input with specific class name and store default texts.
	 */
	'init': function() {
		$$('input.vanish-onclick','textarea.vanish-onclick').each(function(elem) {
			if (!elem.id)
				elem.id = getNewId();
			if (!window.vanish_buffer)
				window.vanish_buffer = {};
			window.vanish_buffer[elem.id] = elem.getValue();
			Event.observe(elem, 'focus', vanishEffect.focus);
			Event.observe(elem, 'blur', vanishEffect.blur);
		}); // we have specified the context
	},
	
	/**
	 * Function executed onFocus - if default text is set, text will be cleard.
	 */
	'focus': function(event) {
		 var elem = Event.element(event);
		 if (elem.getValue() == window.vanish_buffer[elem.id])
			 elem.clear();
	},
	
	/**
	 * Function executed onBlur - if no text is set, default text will be printed.
	 */
	'blur': function(event) {
		 var elem = Event.element(event);
		 if (elem.getValue() == "") {
			 elem.value = window.vanish_buffer[elem.id];
		 }
	}
}

/**
 * Simulation of the target="_blank" in xHTML strict.
 * This will be set automaticly after page is loaded.
 * The input, that should behave like this, should be signed with rel="external".
 */
blank = {
	/**
	 * get all href with rel="external" and set target="_blank"
	 * @TODO: only if the link aims to another host
	 */
	'init': function() {
		$$('a[rel="external"]').each(function(link) {
				if(link.readAttribute('href') != '' && link.readAttribute('href') != '#') {
					link.writeAttribute('target','_blank');
				}
		});
	}
}


ajaxLoaderShow = function() {
	var elem = $('ajax_loader');
	if (elem)
		elem.show();
}


ajaxLoaderHide = function() {
	var elem = $('ajax_loader');
	if (elem)
		elem.hide();
}


/**
 * Returns new ID, that is unused yet.
 */
getNewId = function() {
	var i = 0;
	while ($('autoid_'+i)) {
		i++;
	}
	return 'autoid_'+i;
}


/**
 * Refresh image using some random string in the end.
 */
refreshImage = function (imageSrc, elemId) {
	tmp = new Date();
	tmp = "?"+tmp.getTime();
	$(elemId).src = imageSrc+tmp;
}


/**
 * Function is fired after click on the button to avoid re-sending forms
 */
changeTextAndDisable = function (elem, text) {
	if (text) {
		elem.value = text;
	}
	if (!elem.form.__sent) {
		ajaxLoaderShow();
		elem.form.__sent=1; 
		return true;
	} else {
		return false;
	}
}

elementHeightChange = function (elem, limit, step) {
	var h = elem.getStyle('height');
	var re1 = /(\d+)/g;
	var res = h.match(re1);
	if (res) {
		res = parseInt(res);
		if (step > 0 && res < limit || step < 0 && res > limit) {
			res += step;
		}
		elem.style.height=res.toString() +'px';
	}
}

var sessionIdle = 0;

sessionTimer = function (message, timeout) {
	var step = 60*1000;
	if (sessionIdle >= timeout) {
		alert(message); 
	} else {
		timer = setTimeout("sessionTimer('" + message + "', " + timeout + ")" , step);
		// TODO: we need to count real time.. and not refresh session when some ajax actions are handled...
		sessionIdle += step;
	}
}


/**
 * Use Ajax to get content from page $link using method $useMethod
 * and update element identified with $resultContainer.
 */
ajaxReplace = function (link, useMethod, resultContainer) {
	ajaxLoaderShow();
	new Ajax.Updater(resultContainer, link, { 
		method: useMethod,
		parameters: { __request_type__: 'ajax' },
		onSuccess: function(transport) {
			ajaxLoaderHide();
		}
	});
	return false;
}


/**
 * Use Ajax to get content from page $link using method $useMethod
 * and update element identified with $resultContainer.
 */
ajaxAppend = function (link, useMethod, resultContainer) {
	ajaxLoaderShow();
	new Ajax.Updater(resultContainer, link, { 
		method: useMethod,
		parameters: { __request_type__: 'ajax' },
		insertion: 'bottom',
		onSuccess: function(transport) {
			ajaxLoaderHide();
		}
	});
	return false;
}


/**
 * Use Ajax to get content from page $link using method $useMethod
 * and update element identified with $resultContainer.
 */
ajaxPrepend = function (link, useMethod, resultContainer) {
	ajaxLoaderShow();
	new Ajax.Updater(resultContainer, link, { 
		method: useMethod,
		parameters: { __request_type__: 'ajax' },
		insertion: 'top',
		onSuccess: function(transport) {
			ajaxLoaderHide();
		}
	});
	return false;
}


/**
 * Use Ajax to sent form data and display message in messageContainer.
 */
ajaxSendFormDisplayMessage = function (form) {
	ajaxLoaderShow();
	new Ajax.Request(form.action, {
		method: form.method,
		parameters: form.serialize(true),
		onSuccess: function(transport) {
			var response = transport.responseText.evalJSON();
			if (response['form_result'] == 'OK') {
				//Windows.closeAll();
				if (response['redirect'] != '')
					window.location.href = response['redirect'];
				else {
					window.location.reload();
					scroll(0,0);
				}
			} else if (response['form_result'] == 'ERROR') {
				ajaxLoaderHide();
				messageContainer = $(form.id + '_messagesContainer');
				if (messageContainer) {
					messageContainer.innerHTML = response['messages'].join('<br />');
					messageContainer.addClassName('error');
				}
			}
		},
		onFailure: function() { alert('Something went wrong in ajaxSendFormDisplayMessage...') }
	});
	return false;
}

/**
 * Use Ajax to update a select box values..
 */
ajaxReplaceSelect = function (link, useMethod, resultSelectBox, params) {
	ajaxLoaderShow();
	new Ajax.Request(link, {
		method: useMethod,
		parameters: params,
		onSuccess: function(transport) {
			var responseItems = transport.responseText.evalJSON();
			selectBox = $(resultSelectBox);
			selectedValues={};
			childs=$(resultSelectBox).childNodes;
			for (i=0; i<childs.length; i++) {
				selectedValues[childs[i].value]=childs[i].selected;
			} 
			selectBox.innerHTML='';
			for (i=0; i<responseItems.length; i++) {
				var el = new Element('option', {value: responseItems[i]['value']});
				if (selectedValues[responseItems[i]['value']])
					el.selected = true;
				el.innerHTML = responseItems[i]['text'];
				selectBox.appendChild(el);
			}
			ajaxLoaderHide()
		},
		onFailure: function() { alert('Something went wrong in ajaxReplaceSelect...') }
	});
}


/**
 * Use Ajax to get content from page $link using method $useMethod
 * and update select element's values identified by $resultContainer.
 */
ajaxUpdateSelectBox = function (link, useMethod, resultContainer) {
	ajaxLoaderShow();
	new Ajax.Updater(resultContainer, link, { 
		method: useMethod,
		parameters: { __request_type__: 'ajax' },
		onSuccess: function(transport) {
			ajaxLoaderHide();
		}
	});
	return false;
}


getWindowParams = function(_windowParams, content) {
	if (!_windowParams)
		_windowParams = {};
	if (!_windowParams['width'])
		_windowParams['width'] = 600;
	if (!_windowParams['height'])
		_windowParams['height'] = 500;
	if (!_windowParams['title'])
		_windowParams['title'] = '';
	
	if (!content)
		return _windowParams;
	
	content = content.replace('Dialog.closeInfo()', 'Windows.closeAll()');
	var title = content.match(/##title=([^#]+)##/);
	if (title)
		_windowParams['title'] = title[1];
	var size = content.match(/##size=([0-9]+)x([0-9]+)##/);
	if (size) {
		_windowParams['width'] = size[1];
		_windowParams['height'] = size[2];
	}
	return _windowParams;
}

applyWindowParams = function (_windowParams, win) {
	win.setTitle(_windowParams['title']);
	if (_windowParams['width'] == 'max')
		win.maximize();
	else
		win.setSize(_windowParams['width'], _windowParams['height']);
}

/**
 * Opens a new window and defines what should happen when window is closed.
 * @param _resultAction action called after window is closed. 
 *        Possible values: 'closeReplacesSelect', 'updateSelect', 'closeDoesNothing'
 * Example usage: 
 * <a href="windowPopupAjax('/blog/new', 'resultReplacesSelect', 'contId', 'actionJSONListing')">add new item</a>
 */
windowPopupAjax = function (_link, _resultAction, _resultContainer, _resultLink, _windowParams) {
	_windowParams = getWindowParams(_windowParams);
	var win = new Window({
			className: 'alphacube', 
			url: _link, 
			showEffectOptions: {duration:1.5}
	}); 
	applyWindowParams(_windowParams, win);
	if (!_resultAction) _resultAction = 'closeDoesNothing';
	switch (_resultAction) {
		default:
		case 'closeDoesNothing':
			break;
		case 'closeReplacesSelect':
			win.setCloseCallback(function() {
				ajaxLoaderShow()
				ajaxReplaceSelect(_resultLink, 'get', _resultContainer, null);
				return true;
			});
			break;
	}
	win.showCenter(true);
	return false;
	
}

windowPopupAjaxGetContent = function (_link, _resultAction, _resultContainer, _resultLink, _windowParams) {
	_windowParams = getWindowParams(_windowParams);
	var win = new Window({
			className: 'alphacube', 
			showEffectOptions: {duration:1.5}
	}); 
	ajaxLoaderShow();
	new Ajax.Request(_link,
	  {
		method:'get',
		parameters: { __request_type__: 'ajax' },
		onSuccess: function(transport) {
			var content = transport.responseText || "no response text";
			_windowParams = getWindowParams(_windowParams, content);
			applyWindowParams(_windowParams, win);
		    win.getContent().innerHTML = content; 
		    ajaxLoaderHide();
		    win.showCenter(true);
		},
		onFailure: function(){ alert('Something went wrong in windowPopupAjaxGetContent...') }
	  });
	return false;
	
}

windowPopupAjaxTranslations = function (_link, _translations) {
    ajaxLoaderShow();
	var res = '<table id="translations">';
	var transl = _translations.evalJSON();
	for (var tr in transl)
		if (transl[tr]['id']) {
			var link = _link.replace('XXIDXX', transl[tr]['id']);
			var onclick = '/*Windows.closeAll();*/ windowPopupAjax(\''+link+'\'); return false;';
			res += '<tr><td>'+transl[tr]['result']+'</td><td><a href="#" onclick="'+onclick+'" title="Edit translation with original value \''+transl[tr]['key']+'\'"></a></td></tr>';
		}
	res += '</table>';
	var win = new Window({
			className: 'alphacube', 
			width: 500,
			height: 400,
			title: "Translations",
			showEffectOptions: {duration:1.5}
	}); 
	win.getContent().innerHTML = res;
    ajaxLoaderHide();
	win.showCenter(true);
}

/* end of the file */