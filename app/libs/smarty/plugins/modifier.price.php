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
	// check decimal format
    $decimalPlaces = (int)$decimalPlaces;
    if ($decimalPlaces<0 || $decimalPlaces>100) {
    	throw new Exception("Invalid decimal places.");
    }
    // convert to float
    $string = (float)$string;
    
    // negative values convert to positive, store the sign
    if ($string < 0.0) {
    	$partSig = '-';
    	$string *= -1;
    } else {
    	$partSig = '';
    }
    // separate decimal part and cut to proper decimal places
	$partDec = str_replace('0.', '', sprintf("%.{$decimalPlaces}f", $string - floor($string)));
	// separate integer part
	$partInt = (int)floor($string);
	
	// divide the integer part by $decimalPlaces
    $arrInt = array();
    while ($partInt) {
    	$arrInt[] = $partInt % 1000;
    	$partInt = (int)($partInt/1000);
    }
    // if no integer part, use 0
    if (!$arrInt) {
    	$arrInt[] = '0';
    }
    // compose the number
    return $partSig.implode($thousandDivider, array_reverse($arrInt)).$comma.$partDec;
}

/* vim: set expandtab: */

?>
