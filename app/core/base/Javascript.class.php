<?php

/**
 * Handles including and definition of javascript scripts.
 * Handles wysiwyg definition.
 * Singleton.
 */
class Javascript {
	
	const WYSIWYG_FULL = 1;
	const WYSIWYG_LITE = 2;
	const WYSIWYG_BBCODE = 3;
	const WYSIWYG_MICRO = 4;
	
	const SELECTOR_DIPLAY_MODE_IMAGES = 'image';
	const SELECTOR_DIPLAY_MODE_IMAGES_TEXTS = 'image_text';
	const SELECTOR_DIPLAY_MODE_TEXTS = 'text';
	const SELECTOR_SOURCE_AJAX = 'ajax';
	const SELECTOR_SOURCE_VALUES = 'values';
	
	static private $scripts = array();
	static private $files = array();
	static private $css = array();
	static private $selectors = array();
	static private $wysiwygs = array("full" => array(), "lite" => array(), "bbcode" => array());
	static private $syntaxHighlighters = array();
	static private $tabs = array();
	static private $actualTranslations = array();
	
	
	/**
	 * Adds a JS file to be included
	 */
	public static function addFile($file) {
		self::$files[] = $file;
	}
	

	/**
	 * Adds a JS script to be included
	 */
	public static function addScript($script) {
		self::$scripts[] = $script;
	}
	

	/**
	 * Adds a CSS file to be included
	 */
	public static function addCSS($css) {
		self::$css[] = $css;
	}
	

	/**
	 * Adds a Selector definition to be included
	 */
	public static function addSelector($modelName, $meta, $fieldsSource=null, $definition=null, $addButtonFuncName=null) {
		$name = 'form_' . $meta->getName();
		if (!$definition) {
			$definition = MetaDataContainer::getFieldOptions($modelName, $meta->getName());
		}
		$displayMode = $meta->getSelectorDisplayMode();
		if ($displayMode == null) {
			$displayMode = self::SELECTOR_DIPLAY_MODE_IMAGES_TEXTS;
		}
		self::$selectors[] = array('name' => $name, 'definition' => $definition, 'displayMode' => $displayMode, 'fieldsSource' => $fieldsSource);
		if (Config::Get('SELECTOR_IMMEDIATELY')) {
			return self::getSelectorInitJS(array('name' => $name, 'definition' => $definition, 'displayMode' => $displayMode, 'fieldsSource' => $fieldsSource, 'addButtonFuncName' => $addButtonFuncName));
		} else {
			return null;
		}
	}
	

	/**
	 * Adds tabs definition to be included
	 */
	public static function addTabs($containerId, $firstActiveId) {
		self::$tabs[] = array('containerId' => $containerId, 'firstActiveId' => $firstActiveId);
		if (Config::Get('TABS_IMMEDIATELY')) {
			return self::getTabInitJS(array('containerId' => $containerId, 'firstActiveId' => 'tab_' . $firstActiveId));
		} else {
			return null;
		}
	}
	

	/**
	 * Adds an syntax highlighter instance
	 */
	public static function addSyntaxHighlighter($css) {
		self::$css[] = $css;
	}
	

	/**
	 * Adds all wysiwyg files needed to use wysiwyg
	 */
	public static function addWysiwygFiles() {
		self::addFile(Request::$url['base'] . DIR_LIBS . "mediamanager/MediaManager.js");
		self::addFile(Request::$url['base'] . DIR_LIBS . "tiny_mce/tiny_mce.js");
		self::addFile(Request::$url['base'] . DIR_LIBS . "tiny_mce/tiny_mce_init.js");
	}
	
	
	/**
	 * Adds a wysiwyg instance
	 */
	public static function addWysiwyg($id, $type=self::WYSIWYG_FULL, $params=array()) {
		self::addWysiwygFiles();
		self::$wysiwygs[$type][] = array('id' => $id, 'params' => $params);
	}
	
	
	/**
	 * Adds a translation
	 */
	public static function addTranslation($key, $kind) {
		self::$actualTranslations[] = array('key' => $key, 'kind' => $kind);
	}

	
	/**
	 * Inlcuding all possible files/scripts to the HTML
	 */
	public static function toHTML() {
		$html = '';
		$html .= self::wysiwygsToHTML();
		$html .= self::selectorsToHTML();
		$html .= self::tabsToHTML();
		$html .= self::filesToHTML();
		$html .= self::scriptsToHTML();
		return $html;
	}
	

	/**
	 * List to HTML tags
	 */
	private static function filesToHTML() {
		$html = '';
		foreach (array_unique(self::$files) as $file) {
			$html .= '<script type="text/javascript" src="' . $file . '"></script>' . "\n";
		}
		return $html;
	}


	/**
	 * Scripts to HTML tags
	 */
	private static function scriptsToHTML() {
		$html = '';
		if (self::$scripts) {
			$html .= '<script type="text/javascript">' . "\n";
			foreach (array_unique(self::$scripts) as $script) {
				$html .= $script . "\n";
			}
			$html .= '</script>' . "\n";
		}
		return $html;
	}


	/**
	 * CSS including to HTML tags
	 */
	private static function cssToHTML() {
		$html = '';
		if (self::$css) {
			foreach (array_unique(self::$css) as $css) {
				$html .= '<link rel="stylesheet" href="' . $css . '" type="text/css" media="screen" />' . "\n";
			}
		}
		return $html;
	}


	/**
	 * Returns specified definition of the wysiwyg
	 * @param <int> $type one of the self::WYSIWYG_LITE, self::WYSIWYG_BBCODE, self::WYSIWYG_FULL, self::WYSIWYG_MICRO
	 */
	private static function getWysiwygDefinition($type) {
		switch ($type) {
			case self::WYSIWYG_LITE: return 'tinymce_lite'; break; 
			case self::WYSIWYG_BBCODE: return 'tinymce_bbcode'; break;
			case self::WYSIWYG_MICRO: return 'tinymce_micro'; break;
			default:
			case self::WYSIWYG_FULL: return 'tinymce_full'; break; 
		}
	}
	
	
	/**
	 * Returns table with actual translations to be easy accessible
	 */
	public static function translationsToHTML() {
		if (!count(self::$actualTranslations)) {
			return '';
		}
		$values = array();
		foreach (self::$actualTranslations as $t) {
			$values[] = $t['key'];
			$values[] = $t['kind'];
		}
		$filters = array('(key, kind) IN (' . str_repeat('(?, ?), ', count(self::$actualTranslations)-1) . '(?, ?))');
		$translations = BaseDictionaryModel::Search('BaseDictionaryModel', $filters, $values);
		$out = '<div id="translations_container"><div id="translations_inner"><h3>Texts to translate</h3><div id="translations_content" style="display: none;"><table>';
		if ($translations) {
			foreach ($translations as $t) {
				$out .= 
				 '<tr><td>'.str_replace("'", "\'", htmlspecialchars(Utilities::truncate(strip_tags($t->key), 50)))
				.'</td><td>'.str_replace("'", "\'", htmlspecialchars(Utilities::truncate(strip_tags($t->text), 50)))
				.'</td><td>'.$t->kind
				.'</td><td><a href="'.Request::getLinkItem('Base', 'Dictionary', 'actionEdit', $t).'">Edit</a></td></tr>';
			}
		}
		$out .= '</table></div>'
			. '<a href="#" onclick="$(\'translations_container\').hide();return false;" class="hide">Hide</a>'
			. '<a href="#" onclick="$(\'translations_content\').show();return false;" class="show">Show</a>'
			.  '</div></div>';
		return $out;
	}


	/**
	 * Selectors initializing to HTML tags
	 * @param $sel array with keys name, displayMode, definition
	 */
	private static function getSelectorInitJS($sel) {
		$out = '';
		$c = false;
		$out .= "
		SelectorMenu.setup(
		  {
			selectField: '{$sel['name']}',
			containerField: '{$sel['name']}_container',
			displayMode: '{$sel['displayMode']}',
			attributeFields: 
		";
		if ($sel['fieldsSource'] == self::SELECTOR_SOURCE_AJAX) {
			$out .= 'response.responseText.evalJSON()';
			$out .= ",\n\t\t\t" . 'updateOptions: true';
		} else {
			$out .= str_replace('"', '\'', json_encode($sel['definition']));
		}
		
		if ($sel['addButtonFuncName']) {
			$out .= ",\n\t\t\t addButtonFuncName: {$sel['addButtonFuncName']}";
		}
		
		$out .= "
			});";
			
		return $out;
	}
	
	
	/**
	 * Tabs initializing to HTML tags
	 * @param $def array with keys containerId, firstActiveId
	 */
	private static function getTabInitJS($sel) {
		$out = '';
		$tabsVarName = 'tabs' . Request::getUniqueNumber();
		$out .= "
		var $tabsVarName = new tabset('{$sel['containerId']}'); // name of div to crawl for tabs and panels
		$tabsVarName.autoActivate($('{$sel['firstActiveId']}')); // name of tab to auto-select if none exists in the url
		";
		return $out;
	}
	
	
	/**
	 * Selectors initializing to HTML tags
	 */
	private static function selectorsToHTML() {
		$out = '';
		if (!Config::Get('SELECTOR_IMMEDIATELY')) {
			foreach (self::$selectors as $sel) {
				$out .= self::getSelectorInitJS($sel);
			}
        }
        if (count(self::$selectors)) {
        	self::addFile(Request::$url['base'] . DIR_LIBS . 'selector/selector.js');
			self::addCSS(Request::$url["base"] . DIR_LIBS . 'selector/stylesheets/selector.css');
			if (!Config::Get('SELECTOR_IMMEDIATELY')) {
				self::addScript("Event.observe(window, 'load', function() { $out })");
			}
        }
		return '';
	}
	

	/**
	 * Tabs initializing to HTML tags
	 */
	private static function tabsToHTML() {
		$out = '';
		if (!Config::Get('TABS_IMMEDIATELY')) {
			foreach (self::$tabs as $tab) {
				$out .= self::getTabInitJS($tab);
			}
        }
        if (count(self::$tabs)) {
			self::addPrototype();
			self::addScriptaculous();
			self::addCSS(Request::$url['base'] . DIR_LIBS . 'stereotabs/styles.css');
        	self::addFile(Request::$url['base'] . DIR_LIBS . 'stereotabs/stereotabs.js');
			if (!Config::Get('TABS_IMMEDIATELY')) {
				self::addScript("Event.observe(window, 'load', function() { $out });");
			}
        }
		return '';
	}
	

	/**
	 * Wysiwyg initializing to HTML tags
	 */
	private static function wysiwygsToHTML() {
		foreach (array_keys(self::$wysiwygs) as $k) {
			if (count(self::$wysiwygs[$k])) {
				$elements = array();
				$p = array(
					'lang' => Language::getCode(),
					'cssFile' => 'content.css',
					);
				foreach (self::$wysiwygs[$k] as $i) {
					$elements[] = '"' . $i['id'] . '"';
					// overwrite values by specific params 
					// (later values will be used to all editors of this type)
					$p = array_merge($p, $i['params']);
				}
				self::addScript('tinyMCE.init(tinymce_add_arrays(' . self::getWysiwygDefinition($k) . ', '
					.'{elements: ' . implode(',', $elements) . ', '
					.'document_base_url: "' . Request::$url["base"] . '", '
					.'content_css: "' . 'app/themes/' . Environment::$smarty->get_template_vars("frontendTheme") . '/css/' . $p['cssFile'] . '", '
					.'language : "' . $p['lang'] .'"}));');
			}
		}
		return '';
	}


	/**
	 * Adds all HTML parts to the page
	 * Smarty Filter used after generating the page.
	 */
	public static function addHTML($tpl_output, &$smarty) {
		if (stripos($tpl_output, 'lightbox') !== false) {
			self::addPrototype();
			self::addScriptaculous();
			self::addFile(Request::$url['base'] . DIR_LIBS . 'lightbox/lightbox.js');
			self::addCSS(Request::$url['base'] . DIR_LIBS . 'lightbox/lightbox.css');
		}
		
		if (stripos($tpl_output, '<pre class="highlight"><code>') !== false) {
			self::addFile(Request::$url['base'] . DIR_LIBS . 'highlight/highlight.js');
			self::addFile(Request::$url['base'] . DIR_LIBS . 'highlight/highlight.pack.js');
			self::addCSS(Request::$url["base"] . DIR_LIBS . 'highlight/styles/default.css');
			self::addScript("hljs.tabReplace = '    ';\nhljs.initHighlightingOnLoad();");
		}
		
		if (stripos($tpl_output, 'tooltipOn') !== false) {
			self::addFile(Request::$url['base'] . DIR_LIBS . 'tooltip/tooltip-v0.2.js');
			self::addCSS(Request::$url["base"] . DIR_LIBS . 'tooltip/default.css');
			self::addFile(Request::$url['base'] . DIR_LIBS . 'tooltip/tooltip-init.js');
		}
		
		if (stripos($tpl_output, '<!-- addScriptaculouse -->') !== false) {
			self::addScriptaculous();
		}
		
		$temp = str_replace(
				"<!-- javascript_adding -->", 
				"\n<!-- javascript_adding_begin -->\n" 
					. self::toHTML() 
					. "\n<!-- javascript_adding_end -->\n", 
				$tpl_output);
		return str_replace(
			'<!-- css_adding -->', 
			self::cssToHTML(), 
			$temp
			);
	}
	
	
	/**
	 * Adds a JS library scriptaculous file to be included
	 */
	public static function addScriptaculous() {
		self::addPrototype();
		self::addFile(Request::$url['base'] . DIR_LIBS . 'scriptaculous/scriptaculous.js');
	}
	

	/**
	 * Adds a JS library prototype file to be included
	 */
	public static function addPrototype() {
		self::addFile(Request::$url['base'] . DIR_LIBS . 'prototype.js');
	}
	
	
	public static function addSelectorWindowButton($modelName, $meta, $title, $newButtonName=null) {
		$selectorInit = Javascript::addSelector($modelName, $meta, Javascript::SELECTOR_SOURCE_AJAX, null, $newButtonName);
		$linkReady = Request::getLinkSimple('Base', 'Options', 'actionGetMetaOptions', array(
			'model' => $modelName,
			'field' => $meta->getName()));
		$linkWindow = $meta->getLinkNewItem();
		$linkWindow = Request::getLinkSimple($linkWindow['package'], $linkWindow['controller'], $linkWindow['action']);
		return "this.parentNode.hide()
			var win = new Window({
			className: 'bluelighting', 
			title: '$title', 
			width:600, height:500, 
			url: '$linkWindow', 
			showEffectOptions: {duration:1.5}
			}); 
		win.setCloseCallback(function() {
			$('ajax_loader').show()
			new Ajax.Request('$linkReady', {
			  method:'get',
			  onSuccess: function(response) {
				// Handle the response content...
				$selectorInit
				$('ajax_loader').hide()
			  }
			 });
			 return true;});
		win.showCenter(true);
		return false;";
	
	}
	
	public static function addTimeout($message, $timeout) {
		$timeout *= 1000; // we set it in seconds, but use in JS in micro seconds
		self::addScript("sessionTimer('$message', $timeout);");
	}

}

function Javascript__addHTML($tpl_output, &$smarty) {
	if (isset(Request::$get['translations'])) {
		Javascript::addCSS(Request::$url['base'] . DIR_THEMES . 'Common/css/translations.css');
		$tpl_output = preg_replace("/<\/body>/", Javascript::translationsToHTML() . "</body>", $tpl_output);
	}
	return Javascript::addHTML($tpl_output, $smarty);
}

?>
