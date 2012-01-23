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
 * Abstract Theme
 * Every theme will contain a class, that will be inherited from this one.
 * @var <string> $name name of the Theme
 * @var <array> $templatesDependency dependency of templates used in this theme
 */
class AbstractTheme {

	var $name = '';
	var $templatesDependency = array();
	
	/**
	 * Returns list of all templates that are used by the template 
	 * specified by $tempalte. It works recursively.
	 * When handling ajax request, we won't use any templates not stamped 
	 * with {ajax}.
	 * @param string $template name of the template
	 * @return array List of templates used by this template
	 */
	public function getDependingTemplates($template, $recursionProtection=20, $themeStrict=false) {
		if ($recursionProtection <= 0) {
			throw new Exception('Depending templates resolution has crashed because the recursion is too deep.');
		}
		if (!isset($this->templatesDependency[$template])) {
			// case if we inherit templates from DefaultTemplate
			if (get_class($this) != 'DefaultTheme') {
				$theme = Themes::getTheme('Default');
				return $theme->getDependingTemplates($template, $recursionProtection, $this);
			} else {
				return array();
			}
		} elseif (!is_array($this->templatesDependency[$template])) {
			// case if no subtemplates are set
			return array();
		} else {
			// case if some subtemplates are set
			$result = array();
			foreach ($this->templatesDependency[$template] as $subTemplate) {
				// when we're handling an ajax request and template is not stamped as {ajax}, skip it
				if (preg_match('/\{ajax\}$/', $subTemplate)) {
					$subTemplate = str_replace('{ajax}', '', $subTemplate);
				} elseif (Request::isAjax())
					continue;
				
				$ex = explode('|', $subTemplate);
				if (count($ex) == 2) {
					// another Theme is specified (e.g. Base|header.tpl)
					$subTemplate = $ex[1];
					$theme = Themes::getTheme($ex[0]);
					$result = array_merge($result, $theme->getDependingTemplates($subTemplate, $recursionProtection-1, false));
				} else {
					// no theme is specified, so it is this one
					if ($themeStrict) {
						// some theme has been set before
						$theme = $themeStrict;
					} else {
						// no theme has been set before
						$theme = $this;
					}
					$result = array_merge($result, $theme->getDependingTemplates($subTemplate, $recursionProtection-1));
				}
				$result[] = $subTemplate;
			}
			return $result;
		}
	}
		
}

?>