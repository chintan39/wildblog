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


class GalleryGalleriesModel extends AbstractPagesModel {

	var $package = 'Gallery';
	var $icon = 'gallery', $table = 'galleries';
	private $uploadDone = false;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

    	$this->addMetaData(AtributesFactory::create('imagesupload')
    		->setLabel('Images upload')
			->setDescription('upload one or more images quickly')
			->setType(Form::FORM_UPLOAD_FILE)
			->setUploadDir(DYNAMIC_NAME_PATTERN . '[url]')
			->setUploadMultipleFiles(true)
			->setForceIsInDb(false));

    	$this->addMetaData(AtributesFactory::create('imagesselect')
    		->setLabel('Select image')
			->setDescription('select already uploaded image using manager')
			->setType(Form::FORM_INPUT_IMAGE)
			->setStoreToProp('imageselectsaved')
			->setForceIsInDb(false));

    	$this->addMetaData(AtributesFactory::stdPublished());

		$this->addMetaData(AtributesFactory::create('titleimage')
			->setLabel('Title image')
			->setType(Form::FORM_SPECIFIC_NOT_IN_DB)
			->setOptionsMethod('listSelectSelector')
			->setSelector(true)
			->setUpdateHandleDefault(true)
			->setRenderObject($this)
			->setSelectorDisplayMode(Javascript::SELECTOR_DIPLAY_MODE_IMAGES));
		
		$this->addMetaData(AtributesFactory::create('imagesthumbs')
    		->setLabel('Images in the gallery')
			->setType(Form::FORM_SPECIFIC_NOT_IN_DB)
			->setRenderObject($this)
			->setForceIsInDb(false));
		
		$this->addNonDbProperty('titleimage', false);
		$this->addNonDbProperty('imageselectsaved');
    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('GalleryImagesModel', 'GalleryGalleriesImagesModel', 'gallery', 'image', 'imageGalleryConnection'); // define a many:many relation to Tag through BlogTag
    }
    
    
    /**
     * Gets an image, that is a title image of the gallery
     * Title image is set in gallery_image connecting table as attribute titleimage
     */
    public function FindTitleImage($modelName, $connectModelName) {
    	if (!$this->id) {
    		return null;
    	}
		$imageModel = new $modelName();
		$galleryImageModel = new $connectModelName();
		$extendedTextsJoin = QueryBuilder::getExtendedTextsJoin($imageModel);
		$languageSupportWhere = QueryBuilder::getLanguageWhere($imageModel);
		if ($languageSupportWhere) {
			$languageSupportWhere .= ' AND ';
		}
		$imagesTable = '`' . $imageModel->getTableName() . '`';
		$galleryTable = '`' . $this->getTableName() . '`';
		$galleryImageTable = '`' . $galleryImageModel->getTableName() . '`';
		$query = "
			SELECT $imagesTable.image
			FROM $imagesTable
			RIGHT JOIN $galleryImageTable ON $galleryImageTable.image = $imagesTable.id
			$extendedTextsJoin
			WHERE $languageSupportWhere $galleryImageTable.titleimage = 1 
			AND $galleryImageTable.gallery = {$this->id}
			LIMIT 1
			";
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('FindTitleImage SQL: ' . $query); // QUERY logger
		}
		$imageObj = dbConnection::getInstance()->fetchAll($query);
		if ($imageObj) {
			return $imageObj[0]['image'];
		}
		return null;
    }

    /**
	 * 
	 * @returns int the newly inserted primary key or current id.
	 */
	public function Save($forceSaving=false) 
	{
		$ret = parent::Save($forceSaving);
		
		return $ret;
	}
	
	
	public function Save2() {
		// we don't need to add already connected images
		$oldImages = $this->Find('GalleryImagesModel', array(), array(), array(), array('id'));
		$oldImagesIds = array();
		if ($oldImages) {
			foreach ($oldImages as $image)
				$oldImagesIds[$image->id] = true;
		}
		
		$this->titleimage = $this->getTitleImage();
		// trick -- we don't want to set title image at all if already set
		$firstImage = !$this->titleimage;
		if (isset($this->uploadedFiles['imagesupload']) && is_array($this->uploadedFiles['imagesupload'])) {
			foreach ($this->uploadedFiles['imagesupload'] as $path) {
				list($dir, $file) = Utilities::getDirFileFromPath($path);
				$image = GalleryImagesModel::addImage2db($dir, $file);
				if (!isset($oldImagesIds[$image->id])) {
					$this->Connect($image);
				}
				
				// If no other title image selected, use the first one
				if ($firstImage) {
					$firstImage = false;
					if (!$this->titleimage) {
						$this->titleimage = $image->id;
						$this->saveTitleImage();
					}
				}
			}
		}
		
		// now add images selected using manager (stored in imagesselect field)
		if ($matchedImages = GalleryImagesModel::Search('GalleryImagesModel', array('image = ?'), array($this->imageselectsaved))) {
			$this->Connect($matchedImages[0]);
		}
	}
	

	public function Import($values) {
		$ret = parent::Import($values);
		$this->titleimage = $this->getTitleImage();
		return $ret;
	}

	public function getTableValue() {
		if ($this->titleimage) {
			$thumb = new Thumbnail(null, $this->titleimage->image, 128, 80, 'b');
			$value = '<img src="' . $thumb->getThumbnailImagePath() . '" alt="#" />';
			return $value;
		}
		return '';
	}
	
    
	/**
	 * Note: there is a problem with using $this inside this method. While we use
	 * MetaDataContainer, we have mished up objects, so we use $model to have correct
	 * model to access.
	 */
	public function getFormHTML($formField) {
		$meta = $formField->getMeta();
		$model = $formField->getDataModel();
		$fieldName = $meta->getName();
		$output = '';
		if ($fieldName == 'titleimage') {
			if (!$model->id) {
				$output .= '<span class="note">' . tg('Title image will be able to select after saving.') . '</span>';
			} elseif (!$model->titleimage) {
				$output .= '<span class="note">' . tg('Title image is not set yet, please, set it.') . '</span>';
			} else {
				$thumb = new Thumbnail(null, $model->titleimage->image, 160, 160, 'c');
				$thumbUrl = $thumb->getThumbnailImagePath();
				$origUrl = $thumb->getOriginalImagePath();
				$thumbSize = $thumb->getOrigWidth().'x'.$thumb->getOrigHeight().'px';
				$buttons = '';
				$buttons .= '<a href="'.Request::getLinkItem($this->package, 'Images', 'actionEdit', $model->titleimage).'" title="'.tg('Edit image').'">'
					.'<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL . '24/edit.png" alt="Edit" />'
					."</a>\n";
				$buttons .= '<a href="'.$origUrl.'" title="'.tg('View image').'" rel="lightbox[titleimage]">'
					.'<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL . '24/view.png" alt="View" />'
					."</a>\n";
				$output .= "<div class=\"simplethumb\">\n";
				$output .= "<img src=\"{$thumbUrl}\" alt=\"{$model->titleimage->image}\" title=\"{$model->titleimage->title}\" />\n";
				$output .= "<span class=\"text\"><strong>{$model->titleimage->title}</strong><br />{$thumbSize}</span>\n";
				$output .= "<span class=\"buttons\">{$buttons}</span>\n";
				$output .= "</div>\n";
			}
		} elseif ($fieldName == 'imagesthumbs') {
			if (!$model->id) {
				$output .= '<span class="note">' . tg('Title image will be able to select after saving.') . '</span>';
			} else {
				$images = $model->Find('GalleryImagesModel');
				if (!$images) {
					$output .= '<span class="note">' . tg('Upload images from your computer or select already uploaded images using the manager.') . '</span>';
				} else {
					$output .= "\n".'<div class="clear"></div>'."\n";
					foreach ($images as $image) {
						$thumb = new Thumbnail(null, $image->image, 160, 160, 'c');
						$thumbUrl = $thumb->getThumbnailImagePath();
						$origUrl = $thumb->getOriginalImagePath();
						$thumbSize = $thumb->getOrigWidth().'x'.$thumb->getOrigHeight().'px';
						$fakeObject = new stdClass;
						$fakeObject->image = $image->id;
						$fakeObject->gallery = $model->id;
						$buttons = '';
						$buttons .= '<a href="'.Request::getLinkItem($this->package, 'GalleriesImages', 'actionSetTitle', $fakeObject).'" title="'.tg('Set as title image').'">'
							.'<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL . '24/home.png" alt="Title" />'
							."</a>\n";
						$buttons .= '<a href="'.Request::getLinkItem($this->package, 'Images', 'actionEdit', $image).'" title="'.tg('Edit image').'">'
							.'<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL . '24/edit.png" alt="Edit" />'
							."</a>\n";
						$buttons .= '<a href="'.$origUrl.'" title="'.tg('View image').'" rel="lightbox[images]">'
							.'<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL . '24/view.png" alt="View" />'
							."</a>\n";
						$buttons .= '<a href="'.Request::getLinkItem($this->package, 'GalleriesImages', 'actionRemoveImage', $fakeObject).'" onclick="return confirm(\''.tg('Are you sure to remvoe this image from the gallery?').'\');" title="'.tg('Remove image').'">'
							.'<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL . '24/remove.png" alt="Remove" />'
							."</a>\n";
						$output .= "<div class=\"simplethumb\">\n";
						$output .= "<img src=\"{$thumbUrl}\" alt=\"{$image->image}\" title=\"{$image->title}\" />\n";
						$output .= "<span class=\"text\"><strong>{$image->title}</strong><br />{$thumbSize}</span>\n";
						$output .= "<span class=\"buttons\">{$buttons}</span>\n";
						$output .= "</div>\n";
					}
					$output .= "\n".'<div class="clear"></div>'."\n";
				}
			}
		}
		return $output;
	}

	private function getTitleImage() {
		if (!$this->id) {
			return false;
		}
		
		$galleriesImagesObject = new GalleryGalleriesImagesModel();
		$galleriesImagesTable = '`' . $galleriesImagesObject->getTableName() . '`';
		$galleryID = $this->id;

		$query = "
			SELECT image
			FROM $galleriesImagesTable
			WHERE gallery = $galleryID AND titleimage = 1
			LIMIT 1
			";
		
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('getTitleImage SQL: ' . $query); // QUERY logger
		}
		
		$image = dbConnection::getInstance()->fetchAll($query);
		
		if ($image) {
			return new GalleryImagesModel($image[0]['image']);
		}
		return false;
	}

	static public function clearTitleImage($galleryID) {
		$galleriesImagesObject = new GalleryGalleriesImagesModel();
		$galleriesImagesTable = '`' . $galleriesImagesObject->getTableName() . '`';

		$query = "
			UPDATE $galleriesImagesTable 
			SET titleimage = 0 
			WHERE
			gallery = $galleryID
			";
			
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('setTitleImage SQL: ' . $query); // QUERY logger
		}
		
		$result1 = dbConnection::getInstance()->query($query);
	}
	
	private function saveTitleImage() {
		if (!$this->id) {
			return false;
		}
		
		$galleriesImagesObject = new GalleryGalleriesImagesModel();
		$galleriesImagesTable = '`' . $galleriesImagesObject->getTableName() . '`';
		$galleryID = $this->id;
		$imageID = $this->titleimage;
		
		$this->clearTitleImage($galleryID);
		
		if ($imageID) {
			$query = "
				SELECT id
				FROM $galleriesImagesTable
				WHERE gallery = $galleryID AND image = $imageID
				LIMIT 1
				";
			
			if (Config::Get('DEBUG_MODE')) {
				Benchmark::log('setTitleImage SQL: ' . $query); // QUERY logger
			}
			
			$image = dbConnection::getInstance()->fetchAll($query);
			if ($image) {
				$query = "
					UPDATE $galleriesImagesTable 
					SET titleimage = 1 
					WHERE
					gallery = $galleryID AND image = $imageID
					";
			} else {
				$query = "
					INSERT INTO $galleriesImagesTable 
					(gallery, image, titleimage) 
					VALUES
					($galleryID, $imageID, 1);
					";
			}
			
			if (Config::Get('DEBUG_MODE')) {
				Benchmark::log('setTitleImage SQL: ' . $query); // QUERY logger
			}
			$result2 = dbConnection::getInstance()->query($query);

			return $result1 && $result2;
		}
		
		return $result1;
		
	}

	public function getValueView($fieldName) {
		if ($fieldName == 'imagesselect')
			return '';
		if ($fieldName == 'imagesthumbs') {
			$images = $this->Find('GalleryImagesModel');
			if (!$images) {
				return tg('Upload images from your computer or select already uploaded images using the manager.');
			} else {
				$output = "\n".'<div class="clear"></div>'."\n";
				foreach ($images as $image) {
					$thumb = new Thumbnail(null, $image->image, 160, 160, 'c');
					$thumbUrl = $thumb->getThumbnailImagePath();
					$origUrl = $thumb->getOriginalImagePath();
					$thumbSize = $thumb->getOrigWidth().'x'.$thumb->getOrigHeight().'px';
					$buttons = '';
					$buttons .= '<a href="'.$origUrl.'" title="'.tg('View image').'" rel="lightbox[images]">'
						.'<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL . '24/view.png" alt="View" />'
						."</a>\n";
					$output .= "<div class=\"simplethumb\">\n";
					$output .= "<img src=\"{$thumbUrl}\" alt=\"{$image->image}\" title=\"{$image->title}\" />\n";
					$output .= "<span class=\"text\"><strong>{$image->title}</strong><br />{$thumbSize}</span>\n";
					$output .= "<span class=\"buttons\">{$buttons}</span>\n";
					$output .= "</div>\n";
				}
				$output .= "\n".'<div class="clear"></div>'."\n";
				return $output;
			}
		}
		return parent::getValueView($fieldName);
	}

} 

?>