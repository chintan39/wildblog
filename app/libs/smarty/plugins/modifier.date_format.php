<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');
/**
 * Smarty date_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     date_format<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *         - string: input date string
 *         - format: strftime format for output with these extentions:
 *           %standard - standard format, e.g. 'Mon, 34 Jan 2001 23:01:12 GMT'
 *           %mn - short month number, e.g. '3'
 *           %mnameshort - short month name (translated using tg), e.g. 'Jun'
 *           %mnamelong - long month name (translated using tg), e.g. 'January'
 *         - default_date: default date if $string is empty
 * @link http://smarty.php.net/manual/en/language.modifier.date.format.php
 *          date_format (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string|void
 * @uses smarty_make_timestamp()
 */
function smarty_modifier_date_format($string, $format = '%b %e, %Y', $default_date = '')
{
    if ($string != '') {
        $timestamp = smarty_make_timestamp($string);
    } elseif ($default_date != '') {
        $timestamp = smarty_make_timestamp($default_date);
    } else {
        return;
    }
    if (DIRECTORY_SEPARATOR == '\\') {
        $_win_from = array('%D',       '%h', '%n', '%r',          '%R',    '%t', '%T');
        $_win_to   = array('%m/%d/%y', '%b', "\n", '%I:%M:%S %p', '%H:%M', "\t", '%H:%M:%S');
        if (strpos($format, '%e') !== false) {
            $_win_from[] = '%e';
            $_win_to[]   = sprintf('%\' 2d', gmdate('j', $timestamp));
        }
        if (strpos($format, '%l') !== false) {
            $_win_from[] = '%l';
            $_win_to[]   = sprintf('%\' 2d', gmdate('h', $timestamp));
        }
        $format = str_replace($_win_from, $_win_to, $format);
    }
    
    // some special cases
    $format = str_replace("%mnamelong", Utilities::monthNameLong(date('n', $timestamp)), $format);
    $format = str_replace("%mnameshort", Utilities::monthNameLong(date('n', $timestamp)), $format);
    $format = str_replace("%mn", date('n', $timestamp), $format);

    if ($format == '%relative') {
    	return Utilities::dateRelative($timestamp);
    } elseif ($format == '%standard') {
    	$weakDays = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
    	$monthNames = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

    	$weakDay = $weakDays[gmstrftime('%w', $timestamp)];
    	$monthName = $monthNames[gmstrftime('%m', $timestamp)-1];
    	return gmstrftime("$weakDay, %d $monthName %Y %H:%M:%S GMT", $timestamp);
    } else {
    	return gmstrftime($format, $timestamp);
    }
}

/* vim: set expandtab: */

?>
