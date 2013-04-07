<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {form_button} function plugin
 *
 * Type:     function<br>
 * Name:     form_button<br>
 * Date:     May 21, 2002
 * Purpose:  automate form_field generation
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
 * {mailto address="me@domain.com"}
 * {mailto address="me@domain.com" encode="javascript"}
 * {mailto address="me@domain.com" encode="hex"}
 * {mailto address="me@domain.com" subject="Hello to you!"}
 * {mailto address="me@domain.com" cc="you@domain.com,they@domain.com"}
 * {mailto address="me@domain.com" extra='class="mailto"'}
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.mailto.php {mailto}
 *          (Smarty online manual)
 * @version  1.2
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author   credits to Jason Sweat (added cc, bcc and subject functionality)
 * @param    array
 * @param    Smarty
 * @return   string
 * @todo	 This shouldn't be inside smarty, move it to unique object.
 */
function smarty_function_form_button($params, &$smarty)
{
	$output = "";
	$button = $params['button'];
	$sendAjax = isset($params['sendAjax']) ? $params['sendAjax'] : false;
	$textChange = "";
	$onclick = "window.formProtection=false; return " . ($sendAjax ? "ajaxSendFormDisplayMessage(this.form);" : "changeTextAndDisable(this, '" . tg("Sending...") . "');");
	switch ($button["type"]) {
		case Form::FORM_BUTTON_SAVE: 
			$onclick = " onclick=\"$onclick\"";
			$output .= "<input type=\"submit\" class=\"button positive save\" id=\"form_" . $button["name"] . "\" name=\"" . $button["name"] . "\" value=\"" . tg($button["value"]) . "\"$onclick /> ";
			break;
		case Form::FORM_BUTTON_SUBMIT: 
			$onclick = " onclick=\"$onclick\"";
			$output .= "<input type=\"submit\" class=\"button positive submit\" id=\"form_" . $button["name"] . "\" name=\"" . $button["name"] . "\" value=\"" . tg($button["value"]) . "\"$onclick /> ";
			break;
		case Form::FORM_BUTTON_SEND: 
			$onclick = " onclick=\"$onclick\"";
			$output .= "<input type=\"submit\" class=\"button positive send\" id=\"form_" . $button["name"] . "\" name=\"" . $button["name"] . "\" value=\"" . tg($button["value"]) . "\"$onclick /> ";
			break;
		case Form::FORM_BUTTON_CANCEL: 
			$onclick = (isset($button["onclick"]) && $button["onclick"]) ? " onclick = \"{$button["onclick"]}\"" : "";
			$output .= "<input type=\"submit\" class=\"button negative delete\" id=\"form_" . $button["name"] . "\" name=\"" . $button["name"] . "\" value=\"" . tg($button["value"]) . "\" $onclick/> ";
			break;
		case Form::FORM_BUTTON_CLEAR: 
			$onclick = (isset($button["onclick"]) && $button["onclick"]) ? " onclick = \"{$button["onclick"]}\"" : "";
			$output .= "<input type=\"submit\" class=\"button negative clear\" id=\"form_" . $button["name"] . "\" name=\"" . $button["name"] . "\" value=\"" . tg($button["value"]) . "\" $onclick/> ";
			break;
		case Form::FORM_BUTTON_SAVE_AS: 
			$onclick = " onclick=\"this.form.action='{$button['action']}'; if (confirm('".tg('This will create another similar item. Are you sure to continue?')."')) { $onclick } else { return false; }\"";
			$output .= "<input type=\"submit\" class=\"button saveas clear\" id=\"form_" . $button["name"] . "\" name=\"" . $button["name"] . "\" value=\"" . tg($button["value"]) . "\"$onclick /> ";
			break;
		default: break;
	}
	return $output;
}

/* vim: set expandtab: */

?>
