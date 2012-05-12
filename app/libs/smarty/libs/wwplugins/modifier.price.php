<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Formating price
 */
/**
 * Smarty price modifier plugin
 *
 * Type:     modifier<br>
 * Name:     price<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *         - string: input date string
 *         - format: strftime format for output
 *         - default_date: default date if $string is empty
 * @author   Jan Horak <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string|void
 */
function smarty_modifier_price($string, $decimalPlaces=2, $thousandDivider='&nbsp;', $comma=',')
{
	return Utilities::formatPrice($string, $decimalPlaces, $thousandDivider, $comma);
}

/* vim: set expandtab: */

?>
