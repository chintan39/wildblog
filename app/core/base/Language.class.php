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
 * The class handles the language support, parsing the language from url, etc.2
 * TODO: when adding new language, add all _ext items to db automaticly...
 * TODO: add possibility to edit all languages variations by an item together (this is set by flag by the item - one checkbox) 
 */
class Language {

	static public $instance = null;
	static public $languages = null;
	static public $languageNames = array('cs' => 'Čeština', 'en' => 'English', 'de' => 'Němčina', 'sk' => 'Slovenčina');
	
	static public $actualLanguage = array(Themes::BACK_END => null, Themes::FRONT_END => null);

	/**
	 * Constructor
	 * Loads the languages from DB.
	 */
	function __construct() {
		if (self::$instance) {
			return false;
		}
		
		self::loadLanguages();

		self::$actualLanguage[Themes::FRONT_END] = self::getDefault();
		self::$actualLanguage[Themes::BACK_END] = self::getDefault();
		
		self::$instance = $this;
	}
	
	
	/**
	 * Initialize the singleton.
	 */
	static public function init() {
		if (Config::Get("PROJECT_STATUS") == PROJECT_READY) {
			self::$instance = new Language();
		}
	}

	
	/**
	 * Loads all languages from DB.
	 */
	private function loadLanguages() {
		$m = new BaseLanguagesModel();
		self::$languages = $m->loadLanguages();
		if (!self::$languages) {
			throw new Exception("There have to be at least one language.");
		}
	}
	

	/**
	 * Not clear how this is called and how this should work.
	 * @param array array(path=>array(), base=>, rawPath => ...
	 */
	static public function parseURL(&$url, &$get) {
		if (isset($url['path'][0]) && ($id = self::getIdFromName($url['path'][0]))) {
			array_shift($url['path']);
			self::$actualLanguage[Themes::FRONT_END] = $id;
		}
		if (isset($get['lang']) && ($id = self::getIdFromName($get['lang']))) {
			self::$actualLanguage[Themes::BACK_END] = $id;
		}
	}
	

	/**
	 * Gets id of the language from the name (url)
	 * @param string $name
	 * @return int id of the language
	 */
	static private function getIdFromName($name) {
		foreach (self::$languages as $lang) {
			if ($lang->url == $name) {
				return $lang->id;
			}
		}
		return false;
	}


	/**
	 * Gets name of the language from the id (int)
	 * @param int $id
	 * @return string name of the language
	 */
	static private function getNameFromId($id) {
		foreach (self::$languages as $lang) {
			if ($lang->id == $id) {
				return $lang->url;
			}
		}
		return false;
	}
	

	/**
	 * Returns the default language's id.
	 */
	public function getDefault($branch=Themes::FRONT_END) {
		if ($branch == Themes::BACK_END) {
			return self::getIdFromName(Config::Get("DEFAULT_LANGUAGE_BACK_END"));
		}
		return self::getIdFromName(Config::Get("DEFAULT_LANGUAGE"));
	}
	
	
	/**
	 * Returns actual language for specified content (page base or page content)
	 * @param int $what what to get - Themes::FRONT_END or Themes::BACK_END
	 * @return int language id
	 */
	static public function get($what=false) {
		if ($what === false) {
			$what = Themes::getActualBranch();
		}
		return self::$actualLanguage[$what];
	}
	
	
	/**
	 * Returns code of actual language for specified content (page base or page content)
	 * @param int $what what to get - Themes::FRONT_END or Themes::BACK_END
	 * @return string language code
	 */
	static public function getCode($what=false) {
		return self::getNameFromId(self::get());
	}
	
	
	/**
	 * Returns all languages
	 * @param int $ignore if set, this language will not be contained in the list
	 * @return array languages id and url
	 */
	static public function getAll($ignore=false) {
		$result = array();
		
		foreach (self::$languages as $lang) {
			if (!$ignore || $ignore != $lang->id) {
				$result[] = array(
					'id' => $lang->id, 
					'url' => $lang->url,
					);
			}
		}
		
		return $result;
	}
	
	
	/**
	 * Returns part of the url with language (empty or 'lang/').
	 * @param string|int $lang language id or name (better), if false, actual is used
	 * @return string url part with language (empty or 'lang/')
	 */
	static public function getLangUrl($lang=false) {
		if ($lang === false) {
			$lang = self::$actualLanguage[Themes::FRONT_END];
		}
		if (is_numeric($lang)) {
			$lang = self::getNameFromId($lang);
		}
		if (Config::Get("DEFAULT_LANGUAGE") == $lang) {
			return '';
		} else {
			return $lang . '/';
		}
	}
	
	
	/**
	 * Gets languages avaible to content
	 * @return array Array of languages avaible to content
	 */
	static public function getLanguages($branch=false) {
		$contentLangs = array();
		foreach (self::$languages as $lang) {
			if (($branch==Themes::BACK_END && $lang->back_end) || ($branch==Themes::FRONT_END && $lang->front_end)) {
				$newLang = new stdClass();
				switch ($branch) {
					case Themes::BACK_END: $key = 'lang_backend'; break;
					case Themes::FRONT_END: $key = 'lang_frontend'; break;
				}
				$ac = Request::getRequestAction();
				// if different structure per language, we have to redirect to homepage
				// todo: redirect to package homepage and define the package homepage in all packages 
				if ($branch==Themes::FRONT_END && isset($ac['item']) && is_object($ac['item']) && $ac['item']->languageSupport) {
					if (Themes::getActualBranch() == Themes::BACK_END) {
						try {
							// get link to action listing if exists
							$controllerName = str_replace($ac['item']->package, "", str_replace("Model", "", get_class($ac['item'])));
							$newLang->link = Request::getLinkSimple($ac['item']->package, $controllerName, 'actionListing', array($key => $lang->url));
						} catch (Exception $e) {
							// get link to root
							$newLang->link = Request::getLinkHomePage(array($key => $lang->url))->getLink();
						}
					} else {
						// get link to root
						$newLang->link = Request::getLinkHomePage(array($key => $lang->url))->getLink();
					}
				} else {
					if ($ac['item'] && !is_array($ac['item'])) {
						$itemModel = get_class($ac['item']);
						$newItem = new $itemModel($ac['item']->id, $lang->id);
						$newLang->link = Request::getLinkItem($ac['package'], $ac['controller'], $ac['method'], $newItem, array($key => $lang->url));
					} else {
						$newLang->link = Request::getLinkSimple($ac['package'], $ac['controller'], $ac['method'], array($key => $lang->url));
					}
					//$newLang->link = Request::getSameLink(array($key => $lang->url));
				}
				$newLang->title = $lang->title;
				$newLang->url = $lang->url;
				$newLang->id = $lang->id;
				$newLang->text = $lang->text;
				$newLang->actual = ($lang->id == self::get($branch));
				$contentLangs[] = $newLang;
			}
		} 
		return $contentLangs; 
	}
}

?>
