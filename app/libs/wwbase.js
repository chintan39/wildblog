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

/* end of the file */