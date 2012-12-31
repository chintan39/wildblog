<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * Handles image thumbnails creating.
 */
class GalleryThumbsController extends AbstractBasicController {
	
	public function actionThumbnail($args) {
		// convert url to path
		$path = Utilities::url2path(Request::$url['pathRaw']);
		$thumb = new Thumbnail($path);
		if (!$thumb->checkPermitFile())
			return;
		$thumb->store();
		$thumb->show();
	}
	
	public function actionIcons($args) {
		$size = (int)Request::$url['path'][2];
		$file = Request::$url['path'][3];
		$addSubIcon = false;
		if (preg_match('/(.*)_(accept|add|edit|lock|remove|up|view).png/', $file, $matches)) {
			$addSubIcon = DIR_ICONS_IMAGES_DIR_ORIGINAL . '128/_' . $matches[2] . '.png';
			$originalFile = DIR_ICONS_IMAGES_DIR_ORIGINAL . '128/' . $matches[1] . '.png';
		} else {
			$originalFile = DIR_ICONS_IMAGES_DIR_ORIGINAL . '128/' . $file;
		}
		
		$newFile = DIR_ICONS_IMAGES_DIR_THUMBS_PATH . $size . '/' . $file;
		
		if (!in_array($size, array(16, 24, 32, 48, 64)) || !file_exists($originalFile)) {
			// display default image
			exit;
		}
		
		$originalImage = imagecreatefrompng($originalFile);
		$origW = imagesx($originalImage);
		$origH = imagesy($originalImage);
		if ($origW > $origH) {
			$sizeW = $size;
			$sizeH = round($size * $origH / $origW);
		} else {
			$sizeH = $size;
			$sizeW = round($size * $origW / $origH);
		}

		$newImage = imagecreatetruecolor($sizeW, $sizeH);
		imagealphablending($newImage, false);
		imagefill($newImage, 0, 0, imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 255, 255, 255, 127)));
		imagesavealpha($newImage, true);				
		imagealphablending($newImage, true);
		imagecopyresampled(
			$newImage, 
			$originalImage, 
			0, 0, 
			0, 0,
			$sizeW, $sizeH, 
			$origW, $origH
			);
		if ($addSubIcon !== false) {
			$subIcon = imagecreatefrompng($addSubIcon);
			imagecopyresampled($newImage, $subIcon, 0, 0, 0, 0, $sizeW, $sizeH, 
			$origW, $origH);
			imagedestroy($subIcon);
		}
		
		if (!is_dir(DIR_ICONS_IMAGES_DIR_THUMBS_PATH)) {
			mkdir(DIR_ICONS_IMAGES_DIR_THUMBS_PATH, 0777);
		}
		
		if (!is_dir(DIR_ICONS_IMAGES_DIR_THUMBS_PATH . $size)) {
			mkdir(DIR_ICONS_IMAGES_DIR_THUMBS_PATH . $size, 0777);
		}
		
		imagepng($newImage, $newFile);

		header('Content-type: image/png');
		imagepng($newImage);
		
		exit;
	}
	

}

?>