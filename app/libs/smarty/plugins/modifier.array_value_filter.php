<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty array_value_filter modifier plugin
 *
 * Type:     modifier<br>
 * Name:     array_value_filter<br>
 * Purpose:  filter the array using key/value combination
 * @link http://code.google.com/p/wildblog
 * @author   Jan Horak
 * @param array
 * @param string
 * @param string
 * @param string
 * @param string
 * @return array
 */

function smarty_modifier_array_value_filter($key, $array, $value, $operator='=', $mode='array')
{
    return Utilities::arrayValueFilter($array, $key, $value, $operator, $mode);
}

/* vim: set expandtab: */

?>