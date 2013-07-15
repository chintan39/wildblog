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
 * Store Thumbnail information and properties and can convert between them.
 */
class Thumbnail {

	const MODE_EXACT = 'e';                     // mode exact - aspect ratio is not kept
	const MODE_KEEP_RATIO = 'r';                // aspect ratio is kept
	const MODE_KEEP_RATIO_BACKGROUND = 'b';     // aspect ratio is kept, dimensions are kept and rest space is filled with background
	const MODE_CROP = 'c';     					// aspect ratio is kept, when image has other ration, it is cropped
	const PREFIX_SUFFIX = '_thumb_';            // special string to identify the thumbs
	const DEFAULT_IMAGE = 'default.png';		// this image from DIR_PROJECT_PATH_MEDIA_THUMBS directory will be displayed if original is not set or does not exist
	const DEFAULT_IMAGE_APP = 'default.png';		// this image will be coppied into DIR_PROJECT_PATH_MEDIA_THUMBS directory if no default.png image exists there
	const TRANSPARENT_IMAGE = 'transparent.gif';
	const PERMIT_FILE_SUFFIX = '.permit';
	
	var $originalImagePath;		// relative path
	var $thumbnailImagePath;	// relative path
	var $width;                 // width required
	var $height;                // height required
	var $mode;                  // mode (see MODE_xxx constants)
	var $background='FFFFFF7F'; // color to filled background in hexa (with alpha without 0x prefix); default: transparent white
	                            // caution: alpha has only 7bits, so EF is transparent, 00 is opaque
	var $zoom=null;             // computed zoom (not used if MODE_EXACT)
	var $newWidth=null;         // computed width (in MODE_EXACT same as width, but do not have to be else)
	var $newHeight=null;        // computed height (in MODE_EXACT same as height, but do not have to be else)
	var $origWidth=null;        // original image's width
	var $origHeight=null;       // original image's height
	var $origImage=null;        // original image resource
	var $newImage=null;         // new image resource
	var $willBeStored=true;     // should the thumbnail be stored? if not found - do not store it
	var $permitCreated=false;	// file with .permit suffix is created at a time of calling Thumb->getThumbPath, but only the first time

	/**
	 * Constructor
	 * @param string $thumbnailImagePath
	 * @param string $originalImagePath
	 * @param int $width
	 * @param int $height
	 * @param int $mode
	 * @param string $background
	 */
	public function __construct($thumbnailImagePath=null, $originalImagePath=null, $width=null, $height=null, $mode=null, $background=null) {
		$this->originalImagePath = $originalImagePath;
		$this->thumbnailImagePath = $thumbnailImagePath;
		$this->width = $width;
		$this->height = $height;
		$this->mode = $mode;
		if ($background) {
			$this->background = $background;
		}
		if (!$this->checkDimensions()) {
			throw new Exception("Image's dimensions are wrong.");
		}
		if (($this->originalImagePath === null || !$this->width || !$this->height || !$this->mode) && !$this->thumbnailImagePath) {
			throw new Exception("Image's original path and parameters nor thumbnail path is not set.");
		}
	}

	
	/**
	 * Checks if dimensions of thumbnail are valid. We shouldn't allow to create
	 * arbitrary dimensions, because it would be DOS vulnerability.
	 * Ordinary visitors could waste all the disk space by sending
	 * valid requests with all possible dimensions.
	 * Not implemented yet, better allow only images strictly intended to display.
	 */
	public function checkDimensions() {
		return true;
	}

	
	/**
	 * Stores permit file (thumbnail path + suffix .permit) to sign, that
	 * this thumbnail file can be created.
	 * This should protect of creating arbitrary dimensions, because it
	 * would be DOS vulnerability.
	 * Ordinary visitors could waste all the disk space by sending
	 * valid requests with all possible dimensions.
	 */
	public function createPermitFile() {
		$permitFile = $this->getPermitFilePath();
		if (file_put_contents($permitFile, '') === false)
			throw new Exception("Image's permit file '" . $permitFile . "' couldn't be created.");
	}

	public function checkPermitFile() {
		if (Permission::check(Permission::$CONTENT_ADMIN | Permission::$ADMIN)) {
			$this->createPermitFile();
			return true;
		}
		$permitFile = $this->getPermitFilePath();
		if (!file_exists($permitFile)) {
			throw new Exception("Image's permit file '" . $permitFile . "' doesn't exist.");
			return false;
		}
		return true;
	}

	public function getPermitFilePath() {
		return $this->thumbnailImagePath . self::PERMIT_FILE_SUFFIX;
	}

	
	/**
	 * Setter
	 * @param string $originalImagePath
	 */
	public function setOriginalImagePath($originalImagePath) {
		$this->originalImagePath = $originalImagePath;
	}

	
	/**
	 * Setter
	 * @param string $thumbnailImagePath
	 */
	public function setThumbnailImagePath($thumbnailImagePath) {
		$this->thumbnailImagePath = $thumbnailImagePath;
	}

	
	/**
	 * Setter
	 * @param int $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	
	/**
	 * Setter
	 * @param int $height
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	
	/**
	 * Setter
	 * @param int $mode
	 */
	public function setMode($mode) {
		$this->mode = $mode;
	}

	
	/**
	 * Setter
	 * @param string $background hexa without 0x prefix, with alpha, for example FFDDEEEF
	 */
	public function setBackground($background) {
		$this->background = $background;
	}

	
	/**
	 * Getter with value checking and computing if needed
	 * @return string
	 */
	public function getOriginalImagePath() {
		if ($this->originalImagePath === null) {
			$this->parseThumbnailImagePath();
		}
		return $this->originalImagePath;
	}

	
	/**
	 * Getter with value checking and computing if needed
	 * @return string
	 */
	public function getThumbnailImagePath() {
		if ($this->thumbnailImagePath === null) {
			$this->computeThumbnailImagePath();
		}
		/*
		 * We create permit files after whole template is created (for all images)
		if (!$this->permitCreated) {
			$this->createPermitFile();
			$this->permitCreated = true;
		}
		 */
		return $this->thumbnailImagePath;
	}

	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	public function getWidth() {
		if (!$this->width) {
			$this->parseThumbnailImagePath();
		}
		return $this->width;
	}

	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	public function getHeight() {
		if (!$this->height) {
			$this->parseThumbnailImagePath();
		}
		return $this->height;
	}

	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	public function getMode() {
		if (!$this->mode) {
			$this->parseThumbnailImagePath();
		}
		return $this->mode;
	}

	
	/**
	 * Getter with value checking and computing if needed
	 */
	public function getBackground() {
		if (!$this->background) {
			$this->parseThumbnailImagePath();
		}
		return $this->background;
	}
	
	
	/**
	 * Computes thumbnail image path from width, height, mode, original image path...
	 */
	private function computeThumbnailImagePath() {
		if ($this->getOriginalImagePath() == '' || !file_exists(Utilities::url2path($this->getOriginalImagePath()))) {
			$thumbPath = DIR_PROJECT_PATH_MEDIA_THUMBS . self::DEFAULT_IMAGE;
			if (!file_exists($thumbPath))
				$this->createDefaultThumb($thumbPath);
		} else {
			$thumbPath = str_replace(DIR_PROJECT_PATH_MEDIA, DIR_PROJECT_PATH_MEDIA_THUMBS, Utilities::path2url($this->getOriginalImagePath()));
		}
		if ($thumbPath{0} != '/')
			$thumbPath = '/'.$thumbPath;
		$prefix = $this->getWidth() . 'x' . $this->getHeight() . $this->getMode() . self::PREFIX_SUFFIX;
		$thumbPath = preg_replace('/^('. Utilities::string2regexp(SUBDIR_MEDIA) . ')(.*\/)?([^\/]*)\.([^\/\.]*)$/', '${1}' . SUBDIR_THUMBS  . '${2}' . $prefix . '${3}.${4}', $thumbPath, 1, $count);
		if ($count != 1) { // disable control
			throw new Exception("Regular expression replace error.");
		}
		$this->thumbnailImagePath = $thumbPath;
	}

	
	/**
	 * Copies default thumbnail of app to given path as a default thumbnail of the project
	 */
	private function createDefaultThumb($thumbPath) {
		if (!copy(DIR_CONFIG . self::DEFAULT_IMAGE_APP, $thumbPath)) {
			throw new Exception("Could not copy app default thumbnail from Regular expression replace error.");
		}
	}
	
	
	/**
	 * Gets the width, height, mode, original image path... from thumbnail image path
	 */
	private function parseThumbnailImagePath() {
		if (!preg_match(REGEXP_THUMB_FORMAT, $this->getThumbnailImagePath(), $match)) {
			throw new Exception("Regular expression match error.");
		}
		$prefix = $match[2];
		$path = $match[1];
		if (!preg_match('/^(\d+)x(\d+)([berc])' . self::PREFIX_SUFFIX . '(.*)$/', $prefix, $match)) {
			//ErrorLogger::log(ErrorLogger::ERR_WARNING, "Regular expression match prefix error.");
			header('Content-type: image/gif');
			echo file_get_contents(COMMON_IMAGES_PATH . self::TRANSPARENT_IMAGE);
			exit;
		}
		$this->width = (int)$match[1];
		$this->height = (int)$match[2];
		$this->mode = $match[3];
		$this->originalImagePath = str_replace(DIR_PROJECT_PATH_MEDIA_THUMBS, DIR_PROJECT_PATH_MEDIA, str_replace($prefix, $match[4], $this->getThumbnailImagePath()));
		/*if (!file_exists($this->originalImagePath)) {
			throw new Exception("File {$this->originalImagePath} does not exist.");
		}*/
		if (!$this->checkDimensions()) {
			throw new Exception("Image's dimensions are wrong.");
		}
	}

	
	/**
	 * Returns extention of the file
	 * @return string
	 */
	private function getExtention() {
		return strtolower(substr($this->getThumbnailImagePath(), strrpos($this->getThumbnailImagePath(), '.') + 1));
	}

	
	/**
	 * Generate a new thumbnail file and saves it
	 * //TODO: check security if thumbnail is something like ../../../
	 */
	public function store() {
		Utilities::createPath($this->getThumbnailImagePath());
		switch ($this->getExtention()) {
			case "png":
				imagepng($this->getNewImage(), $this->getThumbnailImagePath());
				break;
			case "jpg":
			case "jpeg":
				imagejpeg($this->getNewImage(), $this->getThumbnailImagePath());
				break;
			case "gif":
				imagegif($this->getNewImage(), $this->getThumbnailImagePath());
				break;
			default:
				throw new Exception("Extention " . $this->getExtention() . " not supported.");
		}
		imagedestroy($this->getNewImage());
		$this->newImage = null;
		imagedestroy($this->getOrigImage());
		$this->image = null;
	}

	
	/**
	 * Fills the image using the background color to keep transparent
	 * @param resource $image
	 */
	private function fillTransparentBackground(&$image) {
			imagealphablending($image, false);
			list($r, $g, $b, $a) = $this->getColorsFromHexa($this->getBackground());
			imagefill($image, 0, 0, imagecolortransparent($image, imagecolorallocatealpha($image, $r, $g, $b, $a)));
			imagesavealpha($image, true);				
			imagealphablending($image, true);
	}
	
	
	/**
	 * From hexa color makes array of colors.
	 * @param string $hexaColor
	 * @return array colors array($r, $g, $b, $a)
	 */
	private function getColorsFromHexa($hexaColor) {
		$r = hexdec(substr($hexaColor, 0, 2));
		$g = hexdec(substr($hexaColor, 2, 2));
		$b = hexdec(substr($hexaColor, 4, 2));
		$a = hexdec(substr($hexaColor, 6, 2));
		return array($r, $g, $b, $a);
	}
	
	
	/**
	 * From one color makes another color (black or white, depending on the 
	 * first color to achieve better contrast)
	 * @param array $colorArray array($r, $g, $b, $a)
	 * @return array colors array($r, $g, $b, $a)
	 */
	private function getContrastColor($colorArray) {
		if ($colorArray[0] + $colorArray[1] + $colorArray[2] < 385) {
			return array(255, 255, 255, 0);
		} else {
			return array(0, 0, 0, 0);
		}
	}

	
	/**
	 * Getter
	 * @return resource
	 */
	private function getNewImage() {
		if ($this->newImage === null) {
			$this->newImage = imagecreatetruecolor($this->getNewWidth(), $this->getNewHeight());
			$this->fillTransparentBackground($this->newImage);
			if ($this->getMode() == self::MODE_CROP) {
				imagecopyresampled(
					$this->newImage, 
					$this->getOrigImage(), 
					0, 0, 
					(int)(($this->getZoom()*$this->getOrigWidth()-$this->getNewWidth())/2), 
					(int)(($this->getZoom()*$this->getOrigHeight()-$this->getNewHeight())/2), 
					$this->getNewWidth(), $this->getNewHeight(),  
					(int)($this->getNewWidth()/$this->getZoom()), 
					(int)($this->getNewHeight()/$this->getZoom()));
			} else {
				imagecopyresampled(
					$this->newImage, 
					$this->getOrigImage(), 
					0, 0, 
					0, 0, 
					$this->getNewWidth(), $this->getNewHeight(),  
					$this->getOrigWidth(), $this->getOrigHeight());
			}
		}
		return $this->newImage;
	}

	
	/**
	 * Reads the image from the file and send it to the browser.
	 */
	public function show() {
	    if (!file_exists($this->getThumbnailImagePath())) {
	    	throw new Exception("File " . $this->getThumbnailImagePath() . " does not exists.");
	    } else {
			header('Content-type: image/'.$this->getExtention());
			header('Content-Disposition: inline; filename="' . $this->getThumbnailImagePath() . '"');
			echo file_get_contents($this->getThumbnailImagePath());
			if (!$this->willBeStored) {
				unlink($this->getThumbnailImagePath());
			}
			exit;
	    }
	}

	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	public function getOrigWidth() {
		if ($this->origWidth === null) {
			$this->getOrigSize();
		}
		return $this->origWidth;
	}
	
	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	public function getOrigHeight() {
		if ($this->origHeight === null) {
			$this->getOrigSize();
		}
		return $this->origHeight;
	}
	
	
	/**
	 * Getter with value checking and computing if needed
	 * @return resource
	 */
	private function getOrigImage() {
		if ($this->origImage === null) {
			if (!file_exists(Utilities::url2path($this->originalImagePath))) {
				/*$this->origImage = imagecreatetruecolor($this->getWidth(), $this->getHeight());
				$this->fillTransparentBackground($this->origImage);
				list($r, $g, $b, $a) = $this->getContrastColor($this->getColorsFromHexa($this->getBackground()));
				$textColor = imagecolorallocate($this->origImage, $r, $g, $b);
				imagestring($this->origImage, 1, 5, 5,  tg("Image not found"), $textColor);*/
				$this->origImage = imagecreatefrompng(DIR_CONFIG . self::DEFAULT_IMAGE_APP);
				$this->willBeStored = false;
			} else {
				switch ($this->getExtention()) {
					case "png":
						$this->origImage = imagecreatefrompng(Utilities::url2path($this->getOriginalImagePath()));
						break;
					case "jpg":
					case "jpeg":
						$this->origImage = imagecreatefromjpeg(Utilities::url2path($this->getOriginalImagePath()));
						break;
					case "gif":
						$this->origImage = imagecreatefromgif(Utilities::url2path($this->getOriginalImagePath()));
						break;
					default:
						throw new Exception("Extention " . $this->getExtention() . " not supported.");
				}
			}
		}
		return $this->origImage;
	}

	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	private function getOrigSize() {
		$this->origWidth = imagesx($this->getOrigImage());
		$this->origHeight = imagesy($this->getOrigImage());
	}
	
	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	private function getNewWidth() {
		if ($this->newWidth === null) {
			if ($this->getMode() == self::MODE_CROP) {
				$this->newWidth = $this->getWidth();
			}
			elseif ($this->getZoom()) {
				$this->newWidth = (int)($this->getZoom() * $this->getOrigWidth());
			} else {
				$this->newWidth = $this->getWidth();
			}
			if ($this->newWidth == 0) {
				$this->newWidth = 1;
			}
		}
		return $this->newWidth;
	}
	
	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	private function getNewHeight() {
		if ($this->newHeight === null) {
			if ($this->getMode() == self::MODE_CROP) {
				$this->newHeight = $this->getHeight();
			}
			elseif ($this->getZoom()) {
				$this->newHeight = (int)($this->getZoom() * $this->getOrigHeight());
			} else {
				$this->newHeight = $this->getHeight();
			}
			if ($this->newHeight == 0) {
				$this->newHeight = 1;
			}
		}
		return $this->newHeight;
	}
	
	
	/**
	 * Getter with value checking and computing if needed
	 * @return int
	 */
	private function getZoom() {
		if ($this->zoom === null) {
			switch ($this->getMode()) {
				case self::MODE_EXACT:
					break;
				case self::MODE_CROP:
					if ($this->getWidth() === 0) {
						$this->zoom = (float)$this->getHeight()/(float)$this->getOrigHeight();
					} else if ($this->getHeight() === 0) {
						$this->zoom = (float)$this->getWidth()/(float)$this->getOrigWidth();
					} else {
						$this->zoom = max((float)$this->getWidth()/(float)$this->getOrigWidth(), (float)$this->getHeight()/(float)$this->getOrigHeight());
					}
					break;
				default:
					if ($this->getWidth() === 0) {
						$this->zoom = (float)$this->getHeight()/(float)$this->getOrigHeight();
					} else if ($this->getHeight() === 0) {
						$this->zoom = (float)$this->getWidth()/(float)$this->getOrigWidth();
					} else {
						$this->zoom = min((float)$this->getWidth()/(float)$this->getOrigWidth(), (float)$this->getHeight()/(float)$this->getOrigHeight());
					}
					break;
			}
			if ($this->zoom !== null && ($this->zoom < 0.001 || $this->zoom > 10000.0)) {
				throw new Exception("Zoom value {$this->zoom} is not allowed.");
			}
		}
		return $this->zoom;
	}
	
}


?>
