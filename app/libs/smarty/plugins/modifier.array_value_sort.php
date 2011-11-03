<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty array_value_sort modifier plugin
 *
 * Type:     modifier<br>
 * Name:     array_value_sort<br>
 * Purpose:  sorting the array using key/value combination
 * @link http://code.google.com/p/wildblog
 * @author   Jan Horak
 * @param array
 * @param string
 * @param string
 * @param string
 * @return array
 */
  
function smarty_modifier_array_value_sort($key, $array, $direction='asc', $mode='array')
{
    return Utilities::arrayValueSort($array, $key, $direction, $mode);
}

/* vim: set expandtab: */

?>
