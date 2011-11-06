<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {require} function plugin
 *
 * Type:     function<br>
 * Name:     require<br>
 * Date:     November 1, 2009
 * Purpose:  including templates using different way (using themes)
 *           encode them.<br>
 * Input:<br>
 *         - file = template file name withou package or path or extention (only "list")
 *         - package = (optional, default "Base") used as a prefix to the file (Base.list)
 *         - theme = (optional, if not specified, the theme of the previous template is used)
 *           specifies the theme, which template file should be got from (Default)
 *
 * Examples:
 * <pre>
 * {require file="list"}
 * {require package="Blog" file="list"}
 * {require theme="Default" file="list"}
 * {require package="Blog" theme="Default" file="list"}
 * </pre>
 * @link http://www.wild-web.eu
 * @version  1.0
 * @author   Jan Horak
 * @param    array
 * @param    Smarty
 * @return   string
 */
function smarty_function_require($params, &$smarty)
{
	if (Request::isAjax() && !isset($params['ajax']))
		return;
	
	// create backup to return it after including
	$thisThemeBackup = $smarty->get_template_vars('thisTheme');
	
	// get the package, branch and theme
	$package = Utilities::assocShift($params, "package", "Base");
	$theme = Utilities::assocShift($params, "theme", $thisThemeBackup);
	$file = Utilities::assocShift($params, "file");

	// get path to template
	$filePath = "file:/" . Themes::getTemplatePath($package, $theme, $file);
	
	// we have to know, which theme we use
	$params["thisTheme"] = $theme;
	
	// include the file
	$smarty->_smarty_include(array(
		'smarty_include_tpl_file' => $filePath,
		'smarty_include_vars' => $params
		));
	
	// restore previous theme to make recursion integrity
	$smarty->assign('thisTheme', $thisThemeBackup);
}

/* vim: set expandtab: */

?>
