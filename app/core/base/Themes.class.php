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
 * This class works with style themes. Uses "branches" to specify 
 * different themes to different parts of the site (back-end, front-end). 
 */
class Themes {

	// branches
	const FRONT_END = 'FRONT_END';
	const BACK_END = 'BACK_END';
	
	static public $themes = array();
	
	static public $tmpBranch = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
	}
	
	
	/**
	 * Return the theme name assigned to the $branch.
	 */
	static public function getThemeFromBranch($branch) {
		// project themes
		switch ($branch) {
			case self::FRONT_END: return Config::Get("THEME_FRONT_END"); break;
			case self::BACK_END: return Config::Get("THEME_BACK_END"); break;
		}
		throw new Exception("Branch $branch is not acceptable.");
		return false;
	}
	
	
	/**
	 * Returns the path to template's files.
	 */
	static public function getTemplateDir($theme) {
		$path = str_replace("[theme]", $theme, DIR_SMARTY_THEME_TEMPLATES);
		if (!file_exists($path) || !is_dir($path) || !is_readable($path)) {
			throw new Exception("Template dir '$path' does not exist in package $package.");
		}
		return BASE_PATH.$path;
	}

	
	/**
	 * Returns the path to template's files.
	 */
	static public function getTemplatePath($package, $theme, $file) {
		$path = str_replace("[theme]", $theme, str_replace("[package]", $package, DIR_SMARTY_THEME_TEMPLATES)) . $package . '.' . $file . '.tpl';
		if (!file_exists($path)) {
			if ($theme != 'Default') {
				return self::getTemplatePath($package, 'Default', $file);
			} else {
				throw new Exception("Template '$file' ('$path') does not exist in package $package: $path.");
			}
		}
		return BASE_PATH.$path;
	}

	
	/**
	 * Returns actual branch.
	 * @return string Actual branch
	 */
	static public function getActualBranch() {
		$ac = Request::getRequestAction();
		if ($ac === null) {
			if (self::$tmpBranch) {
				return self::$tmpBranch;
			} else {
				throw new Exception("Temporary Branch is not set and no action is defined.");
			}
		}
		return $ac['branch'];
	}
	
	
	/**
	 * Returns the actual theme.
	 */
	static public function getActualTheme() {
		return self::getThemeFromBranch(self::getActualBranch());
	}
	
		
	/**
	 * Stores temporary branch.
	 * @param string $branch
	 */
	static public function setTmp($branch) {
		self::$tmpBranch = $branch;
	}
	
	
	/**
	 * Loads the available themes.
	 */
	static public function loadThemes() {
		$dir = DIR_THEMES;
		foreach (Utilities::getFilesWithExtention($dir, false) as $file) {
			require_once($dir . $file . '/' . $file . 'Theme.php');
			$themeClassName = $file.'Theme';
			self::$themes[$file] = new $themeClassName();
		}
	}

	
	/**
	 * Returns the theme specified by name.
	 */
	static public function getTheme($themeName) {
		return self::$themes[$themeName];
	}
	
	
	/**
	 * Returns list of all templates depended on the $template in the theme $themeName.
	 * Depended teplates are used to determine, which subactions should be used in this request.
	 * @param <string> $themeName name of the theme
	 * @param <string> $template name of the template
	 */
	static public function getSubTemplates($themeName, $template) {
		if (is_object($themeName)) {
			$themeName = get_class($themeName);
		}
		$theme = self::$themes[$themeName];
		return array_flip($theme->getDependingTemplates($template));
	}
	
}


?>