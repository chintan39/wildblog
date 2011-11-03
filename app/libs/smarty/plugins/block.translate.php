<?php

/**
 * Smarty plugin for translation of static texts
 * -------------------------------------------------------------
 * File:     block.translate.php
 * Type:     block
 * Name:     translate
 * Purpose:  translate a block of text
 * -------------------------------------------------------------
 */
function smarty_block_translate($params, $content, &$smarty, &$repeat)
{
    return Utilities::smarty_translate($params, $content, $smarty, $repeat);
}

?> 
