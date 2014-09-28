/*
	Wide Angle by Pixelarity
	pixelarity.com @pixelarity
	License: pixelarity.com/license
*/

var settings = {

	// Images
		images: {
			
			/*
				Your slides, in this format:

				'path/to/image.jpg': 'position',

				where 'position' is the vertical/horizontal position (eg. 'center center', 'top left').
				Use wide/short images for best results.
			*/
			
			'/media/slides/slide01.jpg': 'center left',
			'/media/slides/slide02.jpg': 'center center',
			'/media/slides/slide03.jpg': 'top center'

		},

	// Transition speed (in ms)
		speed: 3000,

	// Transition delay (in ms)
		delay: 4500

};

(function($) {

	skel.init({
		reset: 'full',
		breakpoints: {
			'global':	{ range: '*', href: '/app/themes/WideAngle/css/style.css' },
			'desktop':	{ range: '737-', href: '/app/themes/WideAngle/css/style-desktop.css', containers: 1200, grid: { gutters: 50 } },
			'1000px':	{ range: '737-1200', href: '/app/themes/WideAngle/css/style-1000px.css', containers: 1000, grid: { gutters: 30 }, viewport: { width: 1080 } },
			'mobile':	{ range: '-736', href: '/app/themes/WideAngle/css/style-mobile.css', containers: '100%', grid: { collapse: true, gutters: 10 }, viewport: { scalable: false } }
		},
		plugins: {
			layers: {
				navPanel: {
					hidden: true,
					breakpoints: 'mobile',
					position: 'top-left',
					side: 'left',
					animation: 'pushX',
					width: '80%',
					height: '100%',
					clickToClose: true,
					/* Change "index.html" below to whatever your homepage link is (eg. "/") */
					html: '<a href="index.html" class="link depth-0">Home</a><div data-action="navList" data-args="nav"></div>',
					orientation: 'vertical'
				},
				titleBar: {
					breakpoints: 'mobile',
					position: 'top-left',
					side: 'top',
					height: 44,
					width: '100%',
					html: '<span class="toggle" data-action="toggleLayer" data-args="navPanel"></span>'
				}
			}
		}
	});

	$(function() {

		var	$window = $(window);
			
		// Forms (IE<10).
			var $form = $('form');
			if ($form.length > 0) {
				
				$form.find('.form-button-submit')
					.on('click', function() {
						$(this).parents('form').submit();
						return false;
					});
		
				if (skel.vars.IEVersion < 10) {
					$.fn.n33_formerize=function(){var _fakes=new Array(),_form = $(this);_form.find('input[type=text],textarea').each(function() { var e = $(this); if (e.val() == '' || e.val() == e.attr('placeholder')) { e.addClass('formerize-placeholder'); e.val(e.attr('placeholder')); } }).blur(function() { var e = $(this); if (e.attr('name').match(/_fakeformerizefield$/)) return; if (e.val() == '') { e.addClass('formerize-placeholder'); e.val(e.attr('placeholder')); } }).focus(function() { var e = $(this); if (e.attr('name').match(/_fakeformerizefield$/)) return; if (e.val() == e.attr('placeholder')) { e.removeClass('formerize-placeholder'); e.val(''); } }); _form.find('input[type=password]').each(function() { var e = $(this); var x = $($('<div>').append(e.clone()).remove().html().replace(/type="password"/i, 'type="text"').replace(/type=password/i, 'type=text')); if (e.attr('id') != '') x.attr('id', e.attr('id') + '_fakeformerizefield'); if (e.attr('name') != '') x.attr('name', e.attr('name') + '_fakeformerizefield'); x.addClass('formerize-placeholder').val(x.attr('placeholder')).insertAfter(e); if (e.val() == '') e.hide(); else x.hide(); e.blur(function(event) { event.preventDefault(); var e = $(this); var x = e.parent().find('input[name=' + e.attr('name') + '_fakeformerizefield]'); if (e.val() == '') { e.hide(); x.show(); } }); x.focus(function(event) { event.preventDefault(); var x = $(this); var e = x.parent().find('input[name=' + x.attr('name').replace('_fakeformerizefield', '') + ']'); x.hide(); e.show().focus(); }); x.keypress(function(event) { event.preventDefault(); x.val(''); }); });  _form.submit(function() { $(this).find('input[type=text],input[type=password],textarea').each(function(event) { var e = $(this); if (e.attr('name').match(/_fakeformerizefield$/)) e.attr('name', ''); if (e.val() == e.attr('placeholder')) { e.removeClass('formerize-placeholder'); e.val(''); } }); }).bind("reset", function(event) { event.preventDefault(); $(this).find('select').val($('option:first').val()); $(this).find('input,textarea').each(function() { var e = $(this); var x; e.removeClass('formerize-placeholder'); switch (this.type) { case 'submit': case 'reset': break; case 'password': e.val(e.attr('defaultValue')); x = e.parent().find('input[name=' + e.attr('name') + '_fakeformerizefield]'); if (e.val() == '') { e.hide(); x.show(); } else { e.show(); x.hide(); } break; case 'checkbox': case 'radio': e.attr('checked', e.attr('defaultValue')); break; case 'text': case 'textarea': e.val(e.attr('defaultValue')); if (e.val() == '') { e.addClass('formerize-placeholder'); e.val(e.attr('placeholder')); } break; default: e.val(e.attr('defaultValue')); break; } }); window.setTimeout(function() { for (x in _fakes) _fakes[x].trigger('formerize_sync'); }, 10); }); return _form; };
					$form.n33_formerize();
				}

			}

		// Dropdowns.
			$('#nav > ul').dropotron({ 
				offsetY: -50,
				mode: 'fade',
				noOpenerFade: true,
				alignment: 'center'
			});

		// Slider.
			var $slider = $('#slider');
			if ($slider.length > 0) {
				
				var src = settings.images,
					speed = settings.speed,
					delay = settings.delay,
					zIndex = 2, a = [], i, n, x;
				
				// Configure target
					$slider.css('position', 'relative');
					
				// Build slides and add them to target
					for (i in src) {
						
						if (!src.hasOwnProperty(i))
							continue;
					
						x = $('<div></div>');
						
						x
							.css('position', 'absolute')
							.css('z-index', zIndex - 1)
							.css('left', 0)
							.css('top', 0)
							.css('width', '100%')
							.css('height', '100%')
							.css('background-position', src[i])
							.css('background-image', 'url("' + i + '")')
							.css('background-size', 'cover')
							.hide();
							
						x.appendTo($slider);
						
						a.push(x);
					
					}

				// Loop
					i = 0;

					a[i].fadeIn(speed, function() {
						window.setInterval(function() {
							
							n = i + 1;
							if (n >= a.length)
								n = 0;
							
							a[n]
								.css('z-index', zIndex)
								.fadeIn(speed, function() {
									a[i].hide();
									a[n].css('z-index', zIndex - 1);
									i = n;
								});

						}, delay);
					});
			}

	});

})(jQuery);
