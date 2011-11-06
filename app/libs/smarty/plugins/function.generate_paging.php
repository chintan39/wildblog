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
function smarty_function_generate_paging($params, &$smarty)
{
	$output = "";
	$collection = $params["collection"];
	$collectionContainerId = $collection->containerId;
	$class = isset($params["tableClass"]) ? $params["tableClass"] : "cleantable";

	/* 
	 * paging 
	 */
	$output .= "<div class=\"$class\">";
	$output .= "<div class=\"paging\">";

	/* 
	 * paging using ajax
	 */
	$showOnlyKeys = isset($params['showOnlyKeys']) ? explode('|', $params['showOnlyKeys']) : array();
		
	if ($collection && array_key_exists("paging", $collection->data) && $collection->data["paging"]) {
		
		if ($showOnlyKeys)
			$keys = $showOnlyKeys;
		else
			$keys = array('first', 'prev', 'prevList', 'actual', 'nextList', 'next', 'last');
		
		foreach ($keys as $key) {
			
			/* handle onclick action when using ajax */
			if ($collection->pagingAjax) {
				if ($showOnlyKeys && $key == 'next')
					$onclick = " onclick=\"this.hide(); return ajaxAppend(this.href, 'get', '$collectionContainerId');\"";
				elseif ($showOnlyKeys && $key == 'prev')
					$onclick = " onclick=\"return ajaxPrepend(this.href, 'get', '$collectionContainerId');\"";
				else
					$onclick = " onclick=\"return ajaxReplace(this.href, 'get', '$collectionContainerId');\"";
			}
			else
				$onclick = '';
			
			$output .= "\n<div class=\"paging_$key\">\n";
			$output .= "<div class=\"inner\">\n";
			if (array_key_exists($key, $collection->data["paging"])) {
				if (is_array($collection->data["paging"][$key]) && !array_key_exists('value', $collection->data["paging"][$key])) {
					foreach ($collection->data["paging"][$key] as $key2 => $val2) {
						$item = $collection->data["paging"][$key][$key2];
						if (is_array($item) && is_numeric($item["value"])) {
							$output .= "<a class=\"paging_$key\" href=\"";
							$output .= $item["link"] ? $item["link"] : $item;
							$output .= "\" title=\"" . tg("page") . " " . $item["value"] . "\"$onclick>" . $item["value"] . "</a> ";
						}                      
					}
				} elseif (is_array($collection->data["paging"][$key])) {
					$item = $collection->data["paging"][$key];
					if (is_array($item) && is_numeric($item["value"])) {
						$output .= "<a class=\"paging_$key\" href=\"";
						$output .= $item["link"] ? $item["link"] : $item;
						$value = ($key == 'actual') ? $item["value"] : '';
						$output .= "\" title=\"" . tg($key . " page") . "\"$onclick>$value</a> ";
					}
				} else {
					$output .= "<span class=\"paging_$key\">" . $collection->data["paging"][$key] . "</span> ";
				}
			}
			$output .= "\n</div>\n";
			$output .= "</div>\n";
		}
	}
	$output .= "<div class=\"clear\"></div>";
	$output .= "</div>";
	$output .= "</div>";

	
	return $output;
}

/* vim: set expandtab: */

?>
