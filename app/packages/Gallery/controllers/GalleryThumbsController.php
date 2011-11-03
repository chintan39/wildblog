<?php

/**
 * Handles image thumbnails creating.
 */
class GalleryThumbsController extends AbstractBasicController {
	
	public function actionThumbnail($args) {
		// convert url to path
		$path = Utilities::url2path(Request::$url['pathRaw']);
		// create the all path
		$path = preg_replace('/^\/(.*)$/', '$1', $path);
		Utilities::createPath($path);
		// we cut slash characters from the beggining of the path
		// TODO: check if the character '$' can be on the next line - what?
		$thumb = new Thumbnail($path);
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