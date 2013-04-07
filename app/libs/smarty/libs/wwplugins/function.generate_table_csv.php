<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {generate_table} function plugin
 *
 * Type:     function<br>
 * Name:     generate_table<br>
 * Date:     May 21, 2002
 * Purpose:  automate generate_table generation
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
// deklaration
function smarty_function_generate_table_csv($params, &$smarty)
{
	
	$output = "";
	$collection = $params["collection"];
	$metaData = $collection->dm->getMetaData();

	/*
	 * head 
	 */
	$first = true;
	foreach ($collection->data["columns"] as $column) {
		$label = (array_key_exists($column, $metaData) ? tg($metaData[$column]->getLabel()) : "");
		$output .= ($first ? '' : ';') . '"' . str_replace(array('"', "\n"), array('\"', '\n'), $label) . '"';
		$first = false;
	}
	$output .= "\n";
	
	/* 
	 * body 
	 */
	if (is_array($collection->data["items"]) && count($collection->data["items"])) {
		foreach ($collection->data["items"] as $item) {
			$first = true;
			foreach ($collection->data["columns"] as $column) {
				$value = $item->$column;
				$value = $item->getValueViewTable($column);
				$output .= ($first ? '' : ';') . '"' . str_replace(array('"', "\n"), array('\"', '\n'), $value) . '"';
				$first = false;
			}
			$output .= "\n";
		} 
	}

	return $output;
}

/* vim: set expandtab: */

?>
