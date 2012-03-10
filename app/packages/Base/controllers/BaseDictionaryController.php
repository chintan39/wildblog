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


class BaseDictionaryController extends AbstractDefaultController {

	public $order = 2;				// order of the controller (0-10)
	
	var $dict= array(
		BaseDictionaryModel::KIND_PROJECT_SPECIFIC => array(),
		BaseDictionaryModel::KIND_GENERAL => array(),
		BaseDictionaryModel::KIND_URL_PARTS => array()
	);
	private $urlDict= array();
	

	/**
	 * Left Menu Links definition
	 */
	public function getLinksAdminMenuLeft() {
		$analyzeLink = new Link(array(
			'link' => Request::getLinkSimple($this->package, $this->name, "actionAnalyze"), 
			'label' => tg('Dictionary Analyzer'), 
			'title' => tg('analyze translations'), 
			'image' => $this->getIcon()));
		$analyzeLink->setOrder($this->order);
		$analyzeLink->addSuperiorActiveActions($this->package, $this->name, "actionAnalyze");
		$analyzeLink->addSuperiorActiveActions($this->package, $this->name, "actionAnalyzeAdd");
		$analyzeLink->addSuperiorActiveActions($this->package, $this->name, "actionAnalyzeRemove");
		return array_merge(parent::getLinksAdminMenuLeft(), AbstractAdminController::getLinksAdminMenuLeft($this), array($analyzeLink));
	}

	
	/**
	 * Loads the dictionary of the static texts from DB
	 */
	public function loadDict() {
		$model = new $this->model();
		$this->dict = $model->loadDict(Language::get());
	}
	
	
	/**
	 * Loads the dictionary of the static texts from DB
	 */
	public function loadUrlDict() {
		$model = new $this->model();
		$this->urlDict = $model->loadUrlDict();
	}
	
	
	/**
	 * Translate the text using the static vocabulary
	 * @param string $text text to translate
	 * @return string translated text
	 */
	private function pureTranslate($text, $kind, $forceTheme=false) {
		if (Config::Get('DEBUG_MODE')) {
			Javascript::addTranslation($text, $kind);
		}
		if (trim($text) == '') {
			return $text;
		}
		if (strlen($text) > DICTIONARY_KEY_LENGTH) {
			throw new Exception("Text to translate is too long. Maximum length is " . DICTIONARY_KEY_LENGTH . ".");
		}
		if ($forceTheme) {
			if (array_key_exists(Language::get($forceTheme), $this->urlDict) && array_key_exists($text, $this->urlDict[Language::get($forceTheme)])) {
				return $this->urlDict[Language::get($forceTheme)][$text];
			} else {
				if (Config::Get("PROJECT_STATUS") == PROJECT_READY && Config::Get("BASE_DICTIONARY_ADD_ON_NO_ENTRY")) {
					$this->addIfNeeded($text, BaseDictionaryModel::KIND_URL_PARTS);
					$this->urlDict[Language::get($forceTheme)][$text] = $text;
				}
				return $text;
			}
		}
		if (array_key_exists($text, $this->dict[$kind])) {
			return $this->dict[$kind][$text];
		} else {
			if (Config::Get("PROJECT_STATUS") == PROJECT_READY && Config::Get("BASE_DICTIONARY_ADD_ON_NO_ENTRY")) {
				$this->addIfNeeded($text, $kind);
				$this->dict[$kind][$text] = $text;
			}
			return $text;
		}
	}

	
	private	function addIfNeeded($text, $kind) {
		$dict = new $this->model();
		if ($dict->Find($this->model, array("key = ?", "language = ?", "kind = ?"), array($text, Language::get(), $kind)) === false) {
			$dict->key = $text;
			$dict->text = $text;
			$dict->kind = $kind;
			$dict->language = Language::get();
			$dict->Save(false, false);
		} 
	}
	
	/**
	 * Parses the text and find all parameters in the text. Parameters will be returned to replace.
	 * @param string $text text to parse
	 * @return array Array of parameters to replace (pattern => parameter_name).
	 */
	private function translateGetParams($text) {
		preg_match_all(REGEXP_TRANSLATE_PARAMETER, $text, $matches);
		$variables = array();
		foreach ($matches[0] as $k => $all) {
			$variables[$all] = $matches[1][$k];
		}
		return $variables;
	}

	
	/**
	 * Smarty block function to translate block of text.
	 * @param array $params paramters of the block tag
	 * @param string $content text to translate (content of the tag)
	 * @param object $smarty Smarty object
	 * @param bool $repeat True if first tag, false else.
	 * @return string Translated text if closing tag. Nothing when opening tag.
	 */
	public function smarty_translate($params, $content, &$smarty, &$repeat, $kind) {
		// only output on the closing tag
		if(!$repeat) {
			if (isset($content)) {
				$content = $this->pureTranslate($content, $kind);
				$variables = $this->translateGetParams($content);
				foreach ($variables as $paramRepl => $paramName) {
					$value = $smarty->get_template_vars($paramName);
					$content = str_replace($paramRepl, $value, $content);
				}
				return $content;
			}
		}
	}

	
	/**
	 * Translates static text. This can be called by controller for example.
	 * @param string $content Text to translate
	 * @param array $params parameters, that can be replaced in the translated text (for example number).
	 * @return string Translated text with replaced parameters.
	 */
	public function translate($content, $params, $kind, $forceTheme=false) {
		$content = $this->pureTranslate($content, $kind, $forceTheme);
		$variables = $this->translateGetParams($content);
		foreach ($variables as $paramRepl => $paramName) {
			$value = isset($params[$paramName]) ? $params[$paramName] : '';
			$content = str_replace($paramRepl, $value, $content);
		}
		return $content;
	}
	
	
	/**
	 * Utility to get static texts to translate
	 */
	public function actionAnalyze() {
		
		$stat = $this->analyze(false, false);
		
		$text = '';
		$text .= "<p>{$stat['foundTemplate']} translations have been found in templates, {$stat['addedTemplate']} should be added.</p>";
		$text .= "<p>{$stat['foundSource']} translations have been found in source, {$stat['addedSource']} should be added.</p>";
		
		$text .= "<h3>All texts:</h3>";
		$text .= "<p class=\"italic\">" . implode("<br />\n", $stat['allTexts']) . "</p>";
		$text .= "<h3>Texts no more used:</h3>";
		$text .= "<p><a href=\"" . Request::getLinkSimple($this->package, $this->name, "actionAnalyzeRemove") . "\">";
		$text .= "<img src=\"" . DIR_THEMES . "/Common/images/ico/64/" . $this->getIcon() . "_remove.png\" alt=\"Remove these items\" />";
		$text .= "</a></p>";
		$text .= "<p class=\"italic\">" . implode("<br />\n", $this->getFirst($stat['noMoreUsedTexts'])) . "</p>";
		$text .= "<h3>Texts to add:</h3>";
		$text .= "<p><a href=\"" . Request::getLinkSimple($this->package, $this->name, "actionAnalyzeAdd") . "\">";
		$text .= "<img src=\"" . DIR_THEMES . "/Common/images/ico/64/" . $this->getIcon() . "_add.png\" alt=\"Add these items\" />";
		$text .= "</a></p>";
		$text .= "<p class=\"italic\">" . implode("<br />\n", $this->getFirst($stat['addedTexts'])) . "</p>";

		$this->assign("title", "Dictionary texts analyse");
		$this->assign("text", $text);
	}
	

	/**
	 * Remove all static texts from DB that are not needed any more
	 */
	public function actionAnalyzeRemove() {
		$stat = $this->analyze(false, true);
		$text = '';
		$text .= "<p>" . count($stat['noMoreUsedTexts']) . " translations have been removed.</p>";
		
		if (count($stat['noMoreUsedTexts'])) {
			$text .= "<h3>Texts no more used:</h3>";
			$text .= "<p class=\"italic\">" . implode("<br />\n", $this->getFirst($stat['noMoreUsedTexts'])) . "</p>";
		}

		$this->assign("title", "Dictionary texts removing");
		$this->assign("text", $text);
	}
	

	/**
	 * Add new static texts to DB that are needed
	 */
	public function actionAnalyzeAdd() {
		$stat = $this->analyze(true, false);
		
		$text = '';
		$text .= "<p>" . count($stat['addedTexts']) . " translations  have been added.</p>";
		
		if (count($stat['addedTexts'])) {
			$text .= "<h3>Added texts:</h3>";
			$text .= "<p class=\"italic\">" . implode("<br />\n", $this->getFirst($stat['addedTexts'])) . "</p>";
		}

		$this->assign("title", "Dictionary texts adding");
		$this->assign("text", $text);
	}
	
	function getFirst($input) {
		$o = array();
		foreach ($input as $i) {
			$o[] = $i[0];
		}
		return $o;
	}
	
	/**
	 * Analyse directory
	 */
	private function analyzeDir(&$stat, $dir, $extention, $regExp, $fileType) {
		foreach (Utilities::getFilesWithExtention($dir, $extention, true) as $file) {
			$fileContent = file_get_contents($file);
			if (preg_match_all($regExp, $fileContent, $matches)) {
				$res = array();
				foreach ($matches[2] as $k => $v) {
					switch ($matches[1][$k]) {
						case 'p': $kind = BaseDictionaryModel::KIND_PROJECT_SPECIFIC; break;
						case 'g': $kind = BaseDictionaryModel::KIND_GENERAL; break;
						case 'u': $kind = BaseDictionaryModel::KIND_URL_PARTS; break;
						default: throw new Exception("Unsupported translate kind.");
					}
					$res[] = array($v, $kind);
				}
				$stat['allTexts'] = array_merge($stat['allTexts'], $matches[2]);
				$stat['added'.$fileType] += $this->getTexts2DB($stat, $res);
				$stat['found'.$fileType] += count($matches[2]);
			}
		}
	}
	

	/**
	 * Method covers analysis of all available things
	 */
	private function analyze($shouldAdd=false, $shouldRemove=false) {
		
		$stat = array(
			"foundSource" => 0, 
			"foundTemplate" => 0, 
			"addedSource" => 0, 
			"addedTemplate" => 0, 
			"addedTexts" => array(), 
			"allTexts" => array(),
			"noMoreUsedTexts" => array(),
			);
		
		$this->analyzeDir($stat, DIR_PACKAGES, 'php', '/\Wt([gpu])\s*\(\s*"([^"]*)"/', "Source");
		$this->analyzeDir($stat, DIR_PACKAGES, 'php', '/\Wt([gpu])\s*\(\s*\'([^\']*)\'\s*\)/', "Source");
		$this->analyzeDir($stat, DIR_THEMES, 'tpl', '/\{t([gpu])\}([^\{]*)\{\/t[gpu]\}/', "Template");
		
		// get all in DB and check if they are needed
		$m = new $this->model();
		$allInDb = $m->Find($this->model);
		if ($allInDb) {
			foreach ($allInDb as $item) {
				if (!in_array($item->key, $stat['allTexts'])) {
					$stat['noMoreUsedTexts'][] = array($item->key, $item->kind);
					if ($shouldRemove) {
						$item->DeleteYourself();
					}
				}
			}
		}

		// add new items to db
		if ($shouldAdd) {
			foreach ($stat['addedTexts'] as $key) {
				$this->addIfNeeded($key[0], $key[1]);
			}
		}
		
		return $stat;
	}
	

	/**
	 * Gets the texts that should be added to DB
	 */
	private function getTexts2DB(&$stat, $texts=array()) {
		$count = 0;
		$dict = new $this->model();
		foreach ($texts as $text) {
			if ($dict->Find($this->model, array("key = ?", "language = ?", "kind = ?"), array($text[0], Language::get(), $text[1])) === false) {
				$stat['addedTexts'][] = $text;
				++$count;
			}
		}
		return $count;
	}
	
	
}

?>