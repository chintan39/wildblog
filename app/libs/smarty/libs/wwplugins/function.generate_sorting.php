<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {generate_sorting} function plugin
 *
 * Type:     function<br>
 * Name:     generate_paging<br>
 * Date:     May 21, 2002
 * Purpose:  automate generate_paging generation
 *           encode them.<br>
 * Input:<br>
 *         - address = e-mail address
 *         - text = (optional) text to display, default is address
 *         - encode = (optional) can be one of:
 *                * none : no encoding (default)
 *                * javascript : encode with javascript
 *                * javascript_charcode : encode with javascript charcode
 *                * hex : encode with hexidecimal (no javascript)
 *         - cc = (optional) address(es) to carbon copy
 *         - bcc = (optional) address(es) to blind carbon copy
 *         - subject = (optional) e-mail subject
 *         - newsgroups = (optional) newsgroup(s) to post to
 *         - followupto = (optional) address(es) to follow up to
 *         - extra = (optional) extra tags for the href link
 *
 * Examples:
 * <pre>
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.mailto.php {mailto}
 *          (Smarty online manual)
 * @version  1.2
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author   credits to Jason Sweat (added cc, bcc and subject functionality)
 * @param    array
 * @param    Smarty
 * @return   string
 */
function smarty_function_generate_sorting($params, &$smarty)
{
	$output = "";
	$collection = $params["collection"];
	$class = isset($params["tableClass"]) ? $params["tableClass"] : "cleantable";

	/* 
	 * paging 
	 */
	$output .= "<div class=\"$class\">";
	$output .= "<div class=\"sorting\">";
	foreach ($collection->getSortingLinks() as $column) {
		$output .= "<a class=\"sorting_{$column['name']}\" href=\"{$column['link']}\" title=\"" . t("Sort by:") . $column['label'] . "\">" . t("Sort by:") . $column['label'] . "</a> ";
	}
	$output .= "</div>";
	$output .= "</div>";

	
	return $output;
}

/* vim: set expandtab: */

?>
