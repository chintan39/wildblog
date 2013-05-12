<?php
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
	static private $protectedForms = array();
	static private $onload = array();
	
	
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
	public static function addSelector($formField, $fieldsSource=null, $definition=null, $addButtonFuncName=null) {
		$meta = $formField->getMeta();
		$modelName = $formField->getModelName();
		$name = $formField->getFormPrefix() . $meta->getName();
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
	public static function addTranslation($key, $kind, $id, $result) {
		if ($kind == BaseDictionaryModel::KIND_URL_PARTS && !Config::Get('BASE_DICTIONARY_FAST_TRANSLATE_URL'))
			return;
		$arrayKey = $result.'##'.$id.'##'.$kind.'##'.$key;
		if (!isset(self::$actualTranslations[$arrayKey]))
			self::$actualTranslations[$arrayKey] = array('key' => str_replace('"', '\u0022', $key), 'kind' => $kind, 'id' => $id, 'result' => str_replace('"', '\u0022', $result));
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
		if (self::$onload)
			self::addScript("Event.observe(window, 'load', function() {\n" . implode(";\n", self::$onload) . "\n});");
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
		return str_replace("'", "\'", json_encode(array_values(self::$actualTranslations)));
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
				self::addOnload($out);
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
				self::addOnload($out);
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
					.'content_css: "' . 'app/themes/' . Environment::$smarty->getTemplateVars("frontendTheme") . '/css/' . $p['cssFile'] . '", '
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
			self::addCSS(Request::$url['base'] . DIR_LIBS . 'highlight/styles/default.css');
			self::addScript("hljs.tabReplace = '    ';\nhljs.initHighlightingOnLoad();");
		}
		
		if (stripos($tpl_output, 'cleanform') !== false) {
			self::addCSS(Request::$url["base"] . DIR_THEMES . 'Common/css/cleanform.css');
		}
		
		if (stripos($tpl_output, 'tooltipOn') !== false) {
			self::addFile(Request::$url['base'] . DIR_LIBS . 'tooltip/tooltip-v0.2.js');
			self::addCSS(Request::$url['base'] . DIR_LIBS . 'tooltip/default.css');
			self::addFile(Request::$url['base'] . DIR_LIBS . 'tooltip/tooltip-init.js');
		}
		
		if (stripos($tpl_output, 'selectMedia') !== false) {
			self::addFile(Request::$url['base'] . DIR_LIBS . 'mediamanager/MediaManager.js');
		}
		
		if (stripos($tpl_output, '<!-- addScriptaculouse -->') !== false) {
			self::addScriptaculous();
		}
		
		// specify formIds that should be protect from unwilling escape by
		// <!-- protectedForm:myFormId -->
		if (preg_match_all('/<!-- protectedForm:(\w+) -->/', $tpl_output, $matches) !== false) {
			foreach ($matches[0] as $k => $formId)
				self::addProtectedForm('form'.trim($matches[1][$k]));
		}
		self::protectForms();
		
		if (stripos($tpl_output, 'windowPopup') !== false) {
			self::addWindows();
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
	
	
	public static function addProtectedForm($formId) {
		self::$protectedForms[] = $formId;
	}
	
	public static function protectForms() {
		if (!self::$protectedForms)
			return;
		$output = '';
		$condition = "0 == 1";
		foreach (self::$protectedForms as $form) {
			$output .= "$(\"$form\").serializedEmpty = null;\n";
			$output .= "window.setInterval(function(){ window.formProtection=true; $(\"$form\").serializedEmpty = $(\"$form\").serialize(); }, ".(FORM_PROTECTION_SAVE_SECONDS*1000).");\n";
			$condition .= " || ( $(\"$form\").serializedEmpty != null && $(\"$form\").serializedEmpty != $(\"$form\").serialize())";
		}
		$warning = tg('Are you sure to leave the form without saving?');
		$output .= <<<EOF
// compare clean and actual form contents before leaving
window.onbeforeunload = function (e) {
    if (window.formProtection && ($condition)) {
        return '$warning';
    }
};
EOF;
		self::addOnload($output);
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
	
	
	/**
	 * Include a JS library windows based on prototype
	 */
	public static function addWindows() {
		self::addScriptaculous();
		self::addFile(Request::$url['base'] . DIR_LIBS . 'windows/javascripts/window.js');
		self::addCSS(Request::$url['base'] . DIR_LIBS . 'windows/themes/default.css'); 
		self::addCSS(Request::$url['base'] . DIR_LIBS . 'windows/themes/alphacube.css');
	}
	
	
	public static function addSelectorWindowButton($formField, $title, $newButtonName=null) {
		$meta = $formField->getMeta();
		$modelName = $formField->getModelName();
		$selectorInit = Javascript::addSelector($formField, Javascript::SELECTOR_SOURCE_AJAX, null, $newButtonName);
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
			ajaxLoaderShow()
			new Ajax.Request('$linkReady', {
			  method:'get',
			  onSuccess: function(response) {
				// Handle the response content...
				$selectorInit
				ajaxLoaderHide()
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
	
	public static function addOnload($code) {
		self::$onload[] = $code;
	}

}

function Javascript__addHTML($tpl_output, &$smarty) {
	/*if (isset(Request::$get['translations'])) {
		Javascript::addCSS(Request::$url['base'] . DIR_THEMES . 'Common/css/translations.css');
		$tpl_output = preg_replace("/<\/body>/", Javascript::translationsToHTML() . "</body>", $tpl_output);
	}*/
	$tpl_output = str_replace('##exportDictJSON##', Javascript::translationsToHTML(), $tpl_output);
	return Javascript::addHTML($tpl_output, $smarty);
}

?>
