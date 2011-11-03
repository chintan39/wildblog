<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty thumbnail modifier plugin
 *
 * Type:     modifier<br>
 * Name:     upper<br>
 * Purpose:  convert original image path to thumbnail path using size of the thumbnail
 * @link http://code.google.com/p/wildblog/
 * @author   Jan Horak
 * @param string
 * @return string
 */
function smarty_modifier_thumbnail($origPath, $w=240, $h=200, $m=Thumbnail::MODE_KEEP_RATIO_BACKGROUND)
{
	$thumb = new Thumbnail(null, Utilities::url2path($origPath), $w, $h, $m);
	return Utilities::path2url($thumb->getThumbnailImagePath());
}

?>
