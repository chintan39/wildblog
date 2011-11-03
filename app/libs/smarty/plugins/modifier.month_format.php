<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty month_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     month_format<br>
 * Date:     Feb 24, 2003
 * Purpose:  catenate a value to a variable
 * Input:    string to catenate
 * Example:  {$var|cat:"foo"}
 * @link http://smarty.php.net/manual/en/language.modifier.cat.php cat
 *          (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @version 1.0
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_month_format($month, $format="%m")
{
	switch ($format) {
		case "%m": 
			return (int)$month; 
			break;
		case "%mm": 
			return (((int)$month >= 10) ? (int)$month : '0'.(int)$month); 
			break;
		case "%name": 
			static $monthsArray;
			$monthsArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			return $monthsArray[(int)$month-1];
		case "%nam": 
			static $monthsArrayShort;
			$monthsArray = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
			return $monthsArray[(int)$month-1];
		default:
			break;
	}
}

/* vim: set expandtab: */

?>
