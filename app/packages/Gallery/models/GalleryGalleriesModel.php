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

    	$this->addMetaData(AtributesFactory::create('imagesUpload')
    		->setLabel('Images upload')
			->setDescription('upload one or more images quickly')
			->setType(Form::FORM_UPLOAD_FILE)
			->setUploadDir(DYNAMIC_NAME_PATTERN . '[url]')
			->setUploadMultipleFiles(true)
			->setForceIsInDb(false));

    	$this->addMetaData(AtributesFactory::stdPublished());

		$this->addMetaData(AtributesFactory::create('imageGalleryConnection')
			->setLabel('Images')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelectSelector')
			->setSelector(true)
			->setSelectorDisplayMode(Javascript::SELECTOR_DIPLAY_MODE_IMAGES));
		
		$this->addMetaData(AtributesFactory::create('titleimage')
			->setLabel('Title image')
			->setType(Form::FORM_SPECIFIC_NOT_IN_DB)
			->setOptionsMethod('listSelectSelector')
			->setSelector(true)
			->setUpdateHandleDefault(true)
			->setRenderObject($this)
			->setSelectorDisplayMode(Javascript::SELECTOR_DIPLAY_MODE_IMAGES));
		
		$this->addNonDbProperty('titleimage', false);
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
		
		$this->saveTitleImage();

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
		
		$firstImage = true;
		
		if (isset($this->uploadedFiles['imagesUpload']) && is_array($this->uploadedFiles['imagesUpload'])) {
			foreach ($this->uploadedFiles['imagesUpload'] as $path) {
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
	}
	

	public function Import($values) {
		$ret = parent::Import($values);
		$this->titleimage = $this->getTitleImage();
		return $ret;
	}

	public function getTableValue() {
		if ($this->titleimage) {
			$thumb = new Thumbnail(null, $this->titleimage->image, 120, 80, 'b');
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
			} else {
				// TODO: element ID is not right
				$output .= '<select '.$formField->getIdAttr().' name="' . $fieldName . '">';
				if ($model->id) {
					$images = $model->Find('GalleryImagesModel');
				} else {
					$images = GalleryImagesModel::Search('GalleryImagesModel');
				}
		
				$selectorDefinition = array(array(
								'image' => '', 
								'indent' => isset($o['indent']) ? $o['indent'] : 0));
				
				$output .= '<option value=""'. ((!$model->titleimage) ? ' selected="selected"' : '') . '>' . '[' . tg('not selected') . ']' . '</option>'."\n";
				$script = '';
				if ($images) {
					foreach ($images as $image) {
						$output .= '<option value="' . $image->id . '"'. ($model->titleimage && ($model->titleimage->id == $image->id) ? ' selected="selected"' : ''). '>' . $image->makeSelectTitle() . '</option>'."\n";
						$thumb = new Thumbnail(null, $image->image, 80, 80, 'c'); 
						$selectorDefinition[] = array(
							'image' => $thumb->getThumbnailImagePath(), 
							'indent' => 1);
					}
					$script = Javascript::addSelector($formField, null, $selectorDefinition);
				}
		
				$output .= '</select>'."\n";
				$output .= '<div class="clear"></div>';
				$output .= '<div ' . $formField->getIdAttr('container') . ' class="selector"></div>'."\n";
				$output .= '<div class="clear"></div>';
				if (Config::Get('SELECTOR_IMMEDIATELY')) {
					$output .= '<script type="text/javascript">'."\n";
					$output .= $script;
					$output .= '</script>'."\n";
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

	private function saveTitleImage() {
		if (!$this->id) {
			return false;
		}
		
		$galleriesImagesObject = new GalleryGalleriesImagesModel();
		$galleriesImagesTable = '`' . $galleriesImagesObject->getTableName() . '`';
		$galleryID = $this->id;
		$imageID = $this->titleimage;

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

} 

?>