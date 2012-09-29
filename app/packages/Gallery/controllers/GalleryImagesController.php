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


class GalleryImagesController extends AbstractPagesController {
	
	static public $hashFileName = '.images_hash';
	
	public function actionClearHashes($args) {
		$this->clearHashes(DIR_PROJECT_PATH_MEDIA);
		$this->assign('message', tg("Images' hashes were removed."));
	}
	
	public function actionListing($args) {
		$this->imagesWebDBSynch();
		parent::actionListing($args);
	}
	
	private function imagesWebDBSynch() {
		$this->synchDir(DIR_PROJECT_PATH_MEDIA);
	}
	
	private function removeValueFromArray(&$array, $value) {
		foreach($array as $j => $i) {
			if($i == $value){
				unset($array[$j]);
				return true;
				break;
			}
		}
		return false;
	}
	
	
	private function addImage2db($dir, $file) {
		return GalleryImagesModel::addImage2db($dir, $file);
	}
	
	private function synchDir($dir, $recursive=true) {
		$files = scandir($dir);
		$this->removeValueFromArray($files, self::$hashFileName);
		$hash = sha1(implode(';', $files));
		if (!file_exists($dir . self::$hashFileName) || strcmp(file_get_contents(Utilities::concatPath($dir, self::$hashFileName)), $hash) != 0) {
			foreach ($files as $file) {
				if (is_dir(Utilities::concatPath($dir, $file))) {
					if ($recursive && $file[0] != '.') {
						$this->synchDir(Utilities::concatPath($dir, $file . '/'), $recursive);
					}
				} else if (preg_match('/\.(jpg|jpe|jpeg|gif|png)$/', $file) && !($dummy = GalleryImagesModel::Search('GalleryImagesModel', array('image = ?'), array(Utilities::concatPath($dir, $file))))) {
					$this->addImage2db($dir, $file);
					//echo "Adding " . $dir . $file . "<br />\n";
				}
			}
			file_put_contents(Utilities::concatPath($dir, self::$hashFileName), $hash);
		}
	}

	private function clearHashes($dir) {
		$files = scandir($dir);
		if (file_exists($dir . self::$hashFileName)) {
			unlink($dir . self::$hashFileName);
		}
		foreach ($files as $file) {
			if (is_dir($dir . $file)) {
				if ($file[0] != '.') {
					$this->clearHashes($dir . $file . '/');
				}
			}
		}
	}

	
	/**
	 * Image manager action - display images acording to directory
	 */
	public function actionImageManager($args) {

		$dirPart = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');
		$directory = DIR_PROJECT_PATH_MEDIA . $dirPart;

		$errors = $images = array();
		
		if (!is_dir($directory) || strpos('..', $directory)) {
			$errors[] = "Invalid directory: $directory.";
		} else {
			// add all images to DB first
			$this->synchDir($directory);
			
			$directories = array();
			$files = scandir($directory);
			foreach ($files as $file) {
				if ($file{0} != '.' && is_dir($directory . '/' . $file)) {
					$newDir = new stdClass();
					$newDir->title = $file;
					$newDir->desc = $file;
					$newDir->image = DIR_ICONS_IMAGES_DIR_THUMBS_URL . '64/folder.png';
					$newDir->active = 1;
					$newDir->link = Request::getSameLink(array('dir' => $this->stripPrefix($directory . '/' . $file))); 
					$newDir->class = 'dir';
					$newDir->onclick = '';
					$newDir->ondoubleclick = '';
					$newDir->delete = Request::getLinkSimple($this->package, $this->name, 'actionImageManagerDel', array('dir' => $dirPart, 'type' => $type, 'subdir' => $file));
					$newDir->edit = Request::getLinkSimple($this->package, $this->name, 'actionImageManagerEditDir', array('dir' => $dirPart, 'type' => $type, 'subdir' => $file));
					$directories[] = $newDir;
				}
				
			}
			
			// fetch images from db
			$extRegExp = '';
			if ($type == 'image') {
				$extRegExp = '(bmp|gif|jpg|jpeg|png)';
			}
			$tmpImages = GalleryImagesModel::Search('GalleryImagesModel', array('image REGEXP ?'), array(Utilities::path2url(Utilities::concatPath($directory, '/')) . '[^/]+'.$extRegExp.'$'));
			$images = array();
			if ($tmpImages) {
				foreach ($tmpImages as $image) {
					$newImage = new stdClass();
					$newImage->title = $image->title;
					$newImage->desc = $image->image;
					if ($type == 'image') {
						$thumb = new Thumbnail(null, $image->image, 64, 64, 'c');
						$newImage->image = Utilities::path2url($thumb->getThumbnailImagePath());
					} else {
						if (preg_match('/\.([^\.\/]+)$/', $image->image, $ext)) {
							if (file_exists(DIR_ICONS_IMAGES_DIR_ORIGINAL . '128/filetype_' . $ext[1] . '.png')) {
								$newImage->image = DIR_ICONS_IMAGES_DIR_THUMBS_URL . '64/filetype_' . $ext[1] . '.png';
							} else {
								$newImage->image = DIR_ICONS_IMAGES_DIR_THUMBS_URL . '64/filetype_unknown.png';
							}
						}
					}
					$newImage->active = 1;
					$newImage->link = 'javascript: void(0);';
					$newImage->class = 'item';
					$newImage->onclick = "onSelectItem('{$image->image}', this);";
					$newImage->ondoubleclick = "onOK();";
					if (!preg_match('/([^\/]+)$/', $image->image, $filename)) {
						throw new Exception("Match of '$filename' didn't succeed.");
					}
					$newImage->delete = Request::getLinkSimple($this->package, $this->name, 'actionImageManagerDel', array('dir' => $dirPart, 'type' => $type, 'file' => $filename[1]));
					$newImage->edit = Request::getLinkItem($this->package, $this->name, 'actionImageManagerEditFile', $image, array('dir' => $dirPart, 'type' => $type));
					$images[] = $newImage;
				}
				
			}
			
			// cut last part of the path using regexp and check if it is still subdirectory of DIR_PROJECT_IMAGES_PATH
			$upperDirectory = array();
			if (preg_match('/^(.*)\/([^\/])+$/', $directory, $match) && strpos($match[1] . '/', DIR_PROJECT_PATH_MEDIA) !== FALSE) {
				$newDir = $this->stripPrefix($match[1] . '/');
				if (preg_match('/(.*)\/$/', $newDir, $match)) {
					$newDir = $match[1];
				}
				$upperDirectory = new stdClass();
				$upperDirectory->title = 'Up';
				$upperDirectory->desc = 'Up';
				$upperDirectory->image = DIR_ICONS_IMAGES_DIR_THUMBS_URL . '64/folder_previous.png';
				$upperDirectory->active = 1;
				$upperDirectory->link = Request::getSameLink(array('dir' => $newDir));
				$upperDirectory->class = 'dir';
				$upperDirectory->onclick = '';
				$upperDirectory->ondoubleclick = '';
				$upperDirectory->delete = '';
				$upperDirectory->edit = '';
				$upperDirectory = array($upperDirectory);
			}
			$dirItems = array_merge($upperDirectory, $directories, $images);
		}

		$this->assign('actualDir', $dirPart);
		$this->assign('actualType', $type);
		$this->assign('advanceUploadAppletScript', $this->getAdvanceUploadAppletScript());
		$this->assign('advanceUploadAppletWindowOpenJS', $this->getAdvanceUploadAppletWindowOpenJS());
		
		$this->assign('title', tp('Listing directory') . ': ' . $dirPart);
		$this->assign('errors', $errors);
		$this->assign('dirItems', $dirItems);
	}
	
	
	private function stripPrefix($dir) {
		if (preg_match('/^' . Utilities::string2regexp(DIR_PROJECT_PATH_MEDIA) . '(.*)$/', str_replace('//', '/', $dir), $match)) {
			return $match[1];
		} else {
			throw new Exception("Directory $dir cannot be stripped.");
		}
	}
	

	public function actionImageManagerNewFile($item) {
		return $this->actionImageManagerEditFile($item);
	}
	
	
	public function actionImageManagerEditFile($item) {
		if (!$item) {
			$item = new GalleryImagesUploadModel();
		}
		$form = new Form();
		$form->fill($item);
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');
		
		// handeling the form request
		$form->handleRequest(array(
			'all' => array(
				'package' => $this->package, 
				'controller' => $this->name, 
				'action' => 'actionImageManager', 
				'args' => array ('dir' => $dir, 'type' => $type)),
			'cancel' => array(
				'package' => $this->package, 
				'controller' => $this->name, 
				'action' => 'actionImageManager', 
				'args' => array ('dir' => $dir, 'type' => $type)),
			));
		
		$this->assign('actualDir', $dir);
		$this->assign('actualType', $type);
		$this->assign($form->getIdentifier(), $form->toArray());
		$this->assign('title', tg('Upload new ' . strtolower($this->name)));
	}


	
	public function actionImageManagerNewDir($item) {
		return $this->actionImageManagerEditDir($item);
	}
	
	
	public function actionImageManagerEditDir($item) {
		if (!$item) {
			$item = new GalleryImagesNewDirModel();
		}
		$form = new Form();
		$form->fill($item);
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');
		
		// handeling the form request
		$form->handleRequest(array('all' => array(
			'package' => $this->package, 
			'controller' => $this->name, 
			'action' => 'actionImageManager', 
			'args' => array ('dir' => $dir, 'type' => $type))));
		
		$this->assign('actualDir', $dir);
		$this->assign('actualType', $type);
		$this->assign($form->getIdentifier(), $form->toArray());
		$this->assign('title', tg('New Dir ' . strtolower($this->name)));
	}
	
	
	public function actionImageManagerDel($args) {
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');
		if (isset(Request::$get['file'])) {
			$path = Utilities::concatPath(DIR_PROJECT_PATH_MEDIA, $dir, Request::$get['file']);
			if (file_exists($path)) {
				$tmp = GalleryImagesModel::Search('GalleryImagesModel', array('image = ?'), array(Utilities::path2url($path)));
				if ($tmp) {
					$tmp[0]->DeleteYourself();
					unlink($path);
				}
			}
		} elseif (isset(Request::$get['subdir'])) {
			$path = Utilities::concatPath(DIR_PROJECT_PATH_MEDIA, $dir, Request::$get['subdir']);
			$tmp = scandir($path);
			// scandir returnes . and .. + files, so if there is only hashFileName file, directory is empty..
			if (file_exists($path) && is_dir($path) && count($tmp) == 2 || (count($tmp) == 3 && $tmp[2] == self::$hashFileName)) {
				if (count($tmp) == 3 && $tmp[2] == self::$hashFileName) {
					unlink(Utilities::concatPath($path, self::$hashFileName));
				}
				rmdir($path);
			}
		}
		Request::redirect(Request::getLinkSimple($this->package, $this->name, 'actionImageManager', array('dir' => $dir, 'type' => $type)));
	}
	
	
	private function getAdvanceUploadAppletScript() {
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');
		$handlerLink = Request::getLinkSimple($this->package, $this->name, 'actionJumploaderUploadHandler', array('dir' => $dir, 'type' => $type));
		$advancedUploadLabel = tg('Advanced multiple upload');
		$libDir = DIR_LIBS;
		$baseUrl = Request::$url['base'];
		$commonImagesPath = Environment::$smarty->getTemplateVars("iconsPath");
		$html =<<<EOF
		<a href="#" onclick="$('appletContainer').innerHTML=appletConf;return false" title="$advancedUploadLabel"><img src="{$commonImagesPath}32/image_multi_add.png" alt="new dir" /></a><div id="appletContainer"></div>
		<script type="text/javascript">
			var appletConf = '\
			<applet name="jumpLoaderApplet"\
			code="jmaster.jumploader.app.JumpLoaderApplet.class"\
			archive="{$baseUrl}{$libDir}jumploader/mediautil_z.jar,{$baseUrl}{$libDir}jumploader/sanselan_z.jar,{$baseUrl}{$libDir}jumploader/jumploader_z.jar"\
			width="140"\
			height="32" \
			mayscript>\
				<param name="uc_sendImageMetadata" value="true"/>\
				<param name="uc_imageEditorEnabled" value="true"/>\
				<param name="uc_useLosslessJpegTransformations" value="true"/>\
				<!--<param name="uc_uploadScaledImages" value="true"/> not working properly :( -->\
				<param name="uc_scaledInstanceNames" value="s,l"/>\
				<param name="uc_scaledInstanceDimensions" value="96x96,2000x2000"/>\
				<param name="uc_scaledInstanceQualityFactors" value="800,850"/>\
				<param name="ac_fireUploaderStatusChanged" value="true"/>\
				<param name="uc_imageRotateEnabled" value="true"/>\
				<param name="uc_deleteTempFilesOnRemove" value="true"/>\
				<param name="uc_uploadUrl" value="$handlerLink"/>\
				<param name="ac_mode" value="framed"/>\
			</applet>\
			';
		</script>
EOF;
		return $html;
	}
	
	private function getAdvanceUploadAppletWindowOpenJS() {
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');
		$windowLink = Request::getLinkSimple($this->package, $this->name, 'actionJumploaderUploadWindow', array('dir' => $dir, 'type' => $type));
		$title = tg('Upload multiple files');
		$html = '';
		$html .= "window.open('$windowLink', '$title', 'toolbar=no,location=no,directories=no,menubar=no,personalbar=no,width=750,height=500,scrollbars=no,resizable=yes,modal=yes,dependable=yes,left=30,top=30'); return false;";
		return $html;
	}
	
	public function actionJumploaderUploadWindow($args) {
		$this->assign('uploadAppletHTML', $this->getAdvanceUploadAppletWindowHTML());
	}

	private function getAdvanceUploadAppletWindowHTML() {
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');
		$handlerLink = Request::getLinkSimple($this->package, $this->name, 'actionJumploaderUploadHandler', array('dir' => $dir, 'type' => $type));
		$advancedUploadLabel = tg('Advanced multiple upload');
		$libDir = DIR_LIBS;
		$baseUrl = Request::$url['base'];
		$commonImagesPath = Environment::$smarty->getTemplateVars("iconsPath");
		$html =<<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
  <head>
  <title>{$advancedUploadLabel}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="robots" content="noindex, nofollow" />
  <meta name="author" content="Jan Horák; mailto:horak.jan@centrum.cz" />
  <script language="javascript">
    /**
    * resize applet to fit client area
    * @param applet an applet to resize
    * @param dx a horizontal space to substract from client area width
    * @param dy a vertical space to substract from client area width
    */
    function resizeApplet() {
      var applet = document.jumpLoaderApplet;
      var dx = 0;
      var dy = 7;
      var w_newWidth, w_newHeight;
      var w_maxWidth = 2600, w_maxHeight = 2200;
      var dx = 0;
      var dy = 2;
      if( navigator.appName.indexOf( "Microsoft" ) != -1 ) {
        w_newWidth = document.body.clientWidth;
        w_newHeight = document.body.clientHeight;
      } else {
        var netscapeScrollWidth = 15;
        w_newWidth = window.innerWidth - netscapeScrollWidth;
        w_newHeight = window.innerHeight - netscapeScrollWidth;
      }
      if( w_newWidth > w_maxWidth ) {
        w_newWidth = w_maxWidth;
      }
      if( w_newHeight > w_maxHeight ) {
        w_newHeight = w_maxHeight;
      }
      applet.width = w_newWidth - dx;
      applet.height = w_newHeight - dy;
      applet.setSize( w_newWidth - dx, w_newHeight - dy );
      //window.scroll( 0,0 );
    }
  </script>
</head>
<body
	leftmargin="0"
	topmargin="0"
	marginwidth="0"
	marginheight="0"
	onResize="resizeApplet()"
	onLoad="resizeApplet()"
>
	<applet id="jumpLoaderApplet" name="jumpLoaderApplet"
			code="jmaster.jumploader.app.JumpLoaderApplet.class"
			archive="{$baseUrl}{$libDir}jumploader/mediautil_z.jar,{$baseUrl}{$libDir}jumploader/sanselan_z.jar,{$baseUrl}{$libDir}jumploader/jumploader_z.jar"
			width="800"
			height="600" mayscript>
				<param name="uc_sendImageMetadata" value="true" />
				<param name="uc_imageEditorEnabled" value="true" />
				<param name="uc_useLosslessJpegTransformations" value="true" />
				<!--<param name="uc_uploadScaledImages" value="true"/> not working properly :( -->\
				<param name="uc_scaledInstanceNames" value="s,l" />
				<param name="uc_scaledInstanceDimensions" value="96x96,2000x2000" />
				<param name="uc_scaledInstanceQualityFactors" value="800,850" />
				<param name="ac_fireUploaderStatusChanged" value="true" />
				<param name="uc_imageRotateEnabled" value="true" />
				<param name="uc_deleteTempFilesOnRemove" value="true" />
				<param name="uc_uploadUrl" value="$handlerLink" />
	</applet>

</body>
</html>
EOF;
		return $html;
	}
	
	public function actionJumploaderUploadHandler($args) {
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		$type = (isset(Request::$get['type']) ? Request::$get['type'] : '');

		$thumbnail_prefix = '';
		$thumbnail_dir = '.thumbs';

		//
		//    specify upload directory - storage 
		//    for reconstructed uploaded files
		$upload_dir = Utilities::concatPath(DIR_PROJECT_PATH_MEDIA, $dir);

		//
		//    specify stage directory - temporary storage 
		//    for uploaded partitions
		$stage_dir = Utilities::concatPath(DIR_PROJECT_PATH_MEDIA, $thumbnail_dir, $dir);
		Utilities::createPath($stage_dir);
	
		//
		//    retrieve request parameters
		$file_param_name = 'file';
		$file_name = $_FILES[ $file_param_name ][ 'name' ];
		$file_id = $_POST[ 'fileId' ];
		$partition_index = $_POST[ 'partitionIndex' ];
		$partition_count = $_POST[ 'partitionCount' ];
		$file_length = $_POST[ 'fileLength' ];
	
		//
		//    the $client_id is an essential variable, 
		//    this is used to generate uploaded partitions file prefix, 
		//    because we can not rely on 'fileId' uniqueness in a 
		//    concurrent environment - 2 different clients (applets) 
		//    may submit duplicate fileId. thus, this is responsibility 
		//    of a server to distribute unique clientId values
		//    (or other variable, for example this could be session id) 
		//    for instantiated applets.
		$client_id = $_GET[ 'clientId' ];

		//
		//    move uploaded partition to the staging folder 
		//    using following name pattern:
		//    ${clientId}.${fileId}.${partitionIndex}
		$source_file_path = $_FILES[ $file_param_name ][ 'tmp_name' ];
		$target_file_path = $stage_dir . $client_id . "." . $file_id . 
			"." . $partition_index;
		if( !move_uploaded_file( $source_file_path, $target_file_path ) ) {
			echo "Error: Can't move uploaded file from '$source_file_path' to '$target_file_path'";
			//ErrorLogger::log(ErrorLogger::ERR_WARNING, "Error: Can't move uploaded file from '$source_file_path' to '$target_file_path'");
			return;
		}

		//
		//    check if we have collected all partitions properly
		$all_in_place = true;
		$partitions_length = 0;
		for( $i = 0; $all_in_place && $i < $partition_count; $i++ ) {
			$partition_file = $stage_dir . $client_id . "." . $file_id . "." . $i;
			if( file_exists( $partition_file ) ) {
				$partitions_length += filesize( $partition_file );
			} else {
				$all_in_place = false;
			}
		}
		
		//
		//    issue error if last partition uploaded, but partitions validation failed
		if( $partition_index == $partition_count - 1 &&
				( !$all_in_place || $partitions_length != intval( $file_length ) ) ) {
			echo "Error: Upload validation error";
			//ErrorLogger::log(ErrorLogger::ERR_WARNING, "Error: Upload validation error");
			return;
		}

		//
		//    reconstruct original file if all ok
		if( $all_in_place ) {
			$file = $upload_dir . $client_id . "." . $file_id;
			$file_handle = fopen( $file, 'w' );
			for( $i = 0; $all_in_place && $i < $partition_count; $i++ ) {
				//
				//    read partition file
				$partition_file = $stage_dir . $client_id . "." . $file_id . "." . $i;
				$partition_file_handle = fopen( $partition_file, "rb" );
				$contents = fread( $partition_file_handle, filesize( $partition_file ) );
				fclose( $partition_file_handle );
				//
				//    write to reconstruct file
				fwrite( $file_handle, $contents );
				//
				//    remove partition file
				unlink( $partition_file );
			}
			fclose( $file_handle );
			//
			// rename to original file
			// NB! This may overwrite existing file
			$filename = $filename2resize = $upload_dir . strtolower($file_name);
			rename($file, $filename);
			ErrorLogger::log(ErrorLogger::ERR_WARNING, "Resizing1 $filename");
		
			// if (file is compressed images - large and small one)
			$shouldRemove = false;
			if (preg_match('/\.(jpg|jpeg|gif|png)\.zip$/', $filename)) {
				// unzip file
				$zip = zip_open($filename);
				
				// cut '.zip' from file
				$fileResult = preg_replace('/\.zip$/', '', strtolower($file_name));
				
				// if file exists, we add a number before extention
				$postfix = 0;
				$tmp = $upload_dir . $fileResult;
				while (file_exists($tmp)) {
					$postfix += 1;
					$tmp = $upload_dir . preg_replace('/\.(jpg|jpeg|gif|png)$/', '-' . $postfix . '.$1', $fileResult);
				}
				if ($postfix) {
					$fileResult = preg_replace('/\.(jpg|jpeg|gif|png)$/', '-' . $postfix . '.$1', $fileResult);
				}
				
				if ($zip) {
					while ($zip_entry = zip_read($zip)) {
						
						// decide wether smaller or larger file it is
						$fileRaw = preg_replace('/\.(jpg|jpeg|gif|png)$/', '', zip_entry_name($zip_entry));
						$dest = false;
						switch ($fileRaw) {
							case 's': 
								$dest = $stage_dir . $thumbnail_prefix . $fileResult;
								if (!is_dir($stage_dir)) {
									mkdir($stage_dir);
								}
								break;
							case 'l': 
								$dest = $filename2resize = $upload_dir . $fileResult; 
								break;
						}
			
						// if we found that image in archive, we unzip it
						if ($dest) {
							// read file and write to $dest
									ErrorLogger::log(ErrorLogger::ERR_WARNING, "Resizing2");
							if (zip_entry_open($zip, $zip_entry, "r")) {
								$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
								file_put_contents($dest, $buf);
								zip_entry_close($zip_entry);
								$shouldRemove = true;
							}
						}
				
					}
				
					zip_close($zip);
				
				}
			}
			if ($shouldRemove) {
				unlink($filename);
			}
			if (Utilities::fileIsImage($filename2resize)) {
				ErrorLogger::log(ErrorLogger::ERR_WARNING, "Resizing3");
				Utilities::resizeImageIfNeeded($filename2resize, DEFAULT_UPLOAD_IMAGE_WIDTH, DEFAULT_UPLOAD_IMAGE_HEIGHT);
			}
			
		}

		//
		//	below is trace of request variables
		?>
		<html>
		<body>
			<h1>GET content</h1>
			<pre><?print_r( $_GET );?></pre>
			<h1>POST content</h1>
			<pre><?print_r( $_POST );?></pre>
			<h1>FILES content</h1>
			<pre><?print_r( $_FILES );?></pre>
		</body>
		</html>	
		<?php
		exit;
	}
	
}

?>