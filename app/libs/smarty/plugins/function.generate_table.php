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
function smarty_function_generate_table($params, &$smarty)
{
	
	$output = "";
	$collection = $params["collection"];
	$columnsSortingEnabled = (!isset($params["columns_sorting"]) || $params["columns_sorting"]);
	$class = isset($params["tableClass"]) ? $params["tableClass"] : "cleantable";
	$metaData = $collection->dm->getMetaData();

	// TODO: make this part generally, use it as a function plugin in defaultForm.tpl 
	if ($collection->filterForm) {
		$form = $collection->filterForm;
		
		// show hide effect
		Javascript::addScriptaculous();
		$filterFormId = 'filterForm' . Request::getUniqueNumber();
		$style = $form['issent'] ? '' : 'display: none;';
		$output .= "<a href=\"#\" onclick=\"Effect.toggle('$filterFormId','blind'); return false;\" title=\"" . tg('Filter items') . "\" class=\"filter_icon\"><img src=\"" . $smarty->get_template_vars("iconsPath") . "32/filter.png" . "\" alt=\"\" /></a>\n";
		$output .= "<div id=\"$filterFormId\" style=\"$style\">\n";
		
		$output .= "<form action=\"{$form['action']}\" method=\"{$form['method']}\" class=\"cleanform\">\n";
		if ($form['actionAccomplished'] && is_array($form['actionAccomplished'])) {
			$output .= "<div class=\"confirm\">\n";
			foreach ($form['actionAccomplished'] as $item) {
				$output .= $item."\n";
			}
			$output .= "</div>\n";
		}

		if ((isset($form['messages']['errors']) && $form['messages']['errors']) || (isset($form['messages']['warnings']) &&$form['messages']['warnings'])) {
			$output .= "<div class=\"error\">\n";
			foreach ($form['messages'] as $errorType) {
				foreach ($errorType as $fieldErrors) {
					foreach ($fieldErrors as $item) {
						$output .= $item . "<br />\n";
					}
				}
			}
			$output .= "</div>\n";
		}

		require_once($smarty->_get_plugin_filepath('function', 'form_field'));
		foreach ($form['fields'] as $field) {
			$output .= smarty_function_form_field(array('field' => $field), $smarty);
		}

		require_once($smarty->_get_plugin_filepath('function', 'form_button'));
		$output .= "<div class=\"float-right\">\n";
		foreach ($form['buttons'] as $button) {
			$output .= smarty_function_form_button(array('button' => $button), $smarty);
		}

		$output .= "<div class=\"clear\"></div>\n";
		$output .= "</div>\n";
		$output .= "<div class=\"clear\"></div>\n";
		$output .= "</form>\n";
		$output .= "</div>\n";
	}

	/*
	 * table 
	 */
	$output .= "<div class=\"table_wrap\"><table class=\"$class\" cellspacing=\"0\" cellpadding=\"0\">";
	
	/*
	 * head 
	 */
	$output .= "<thead>";
	$output .= "<tr>";
	$sortingColumns = $collection->getSortingLinks();
	foreach ($collection->data["columns"] as $column) {
		$label = ($metaData && array_key_exists($column, $metaData) ? tg($metaData[$column]->getLabel()) : "&nbsp;");
		if ($columnsSortingEnabled && isset($sortingColumns[$column])) {
			$class = (($sortingColumns[$column]['active']) ? 'sorting_active' : '');
			$class .= (($sortingColumns[$column]['direction']) ? ' sorting_' . $sortingColumns[$column]['direction'] : '');
			$class = ($class ? " class=\"$class\"" : '');
			$label = "<a$class href=\"{$sortingColumns[$column]['link']}\" title=\"" . tg("Sort by:") . $sortingColumns[$column]['label'] . "\">" . $label . "</a> ";
		}
		$output .= "<th>" . $label . "</th>";
	}
	$output .= "</tr>";
	$output .= "</thead>";
	
	/* 
	 * body 
	 */
	 $output .= "<tbody>";
	if (is_array($collection->data["items"]) && count($collection->data["items"])) {
		foreach ($collection->data["items"] as $item) {
			$output .= "<tr>";
			foreach ($collection->data["columns"] as $column) {
				$value = $item->$column;
				$columnClass = array();
				
				$value = $item->getValueView($column);
				
				switch ($column) {
					case "id":
						$columnClass[] = "align-center";
						$columnClass[] = "small";
						break;
					case "buttonsSet":
						$buttons = array();
						if (!empty($value)) {
							foreach ($value as $button) {
								$action = strtolower(str_replace("action", "", $button["action"]));
								$onclick = "";
								if ($button["button"] == ItemCollection::BUTTON_REMOVE) {
									$onclick .= " return confirm('" . tg("Are you sure?") . "')";
								}
								$onclick = ($onclick ? " onclick=\"" . $onclick . "\"" : "");
								switch ($button["button"]) {
									case ItemCollection::BUTTON_MOVEUP:
									case ItemCollection::BUTTON_MOVEDOWN:
										$imageName = str_replace("move", "", $action);
										break;
									default:
										$imageName = /* $collection->dm->getIcon() . "_" .*/ $action;
										break;
								}
								$title = tg('item ' . $imageName);
								$iconSize = ($imageName == 'edit') ? 32 : 16;
								$buttons[] = "<a href=\"" . $button["link"] . "\" title=\"$title\"$onclick><img src=\"" . $smarty->get_template_vars("iconsPath") . "$iconSize/" . $imageName . ".png\" alt=\"$action\" /></a>";
							}
						}
						$value = implode("", $buttons);
						$columnClass[] = "button-set".count($buttons);
						break;
				}
				$columnClass = $columnClass ? " class=\"" . implode(" ", $columnClass) . "\"" : "";
				$output .= "<td$columnClass>" . ($value !== "" ? $value : "&nbsp;") . "</td>";
			}
			$output .= "</tr>";
		} 
	} else {
		$output .= "<tr><td colspan=\"" . count($collection->data["columns"]) . "\">";
		$output .= $collection->alternativeText;
		$output .= "</td></tr>";
	}
	$output .= "</tbody>";
	$output .= "</table></div>";

	/* 
	 * paging 
	 */
	// we need to include function explicitely
	require_once($smarty->_get_plugin_filepath('function', 'generate_paging'));
	$output .= smarty_function_generate_paging($params, $smarty);

	
	return $output;
}

/* vim: set expandtab: */

?>
