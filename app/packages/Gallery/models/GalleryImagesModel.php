<?php

class GalleryImagesModel extends AbstractNodesModel {

	var $package = 'Gallery';
	var $icon = 'image', $table = 'images';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdImage());
		$this->addMetaData(AbstractAttributesModel::stdDescription());
		
		$this->addMetaData(ModelMetaItem::create('imageGalleryConnection')
			->setLabel('Galleries')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('GalleryGalleriesModel', 'GalleryGalleriesImagesModel', 'image', 'gallery', 'imageGalleryConnection'); // define a many:many relation to Tag through BlogTag
    }

    
	/**
	 * Removes 'media/' from the beginning of path
	 */
	private function removeMainDir($path) {
		return preg_replace('/^' . Utilities::string2regexp(SUBDIR_MEDIA) . '/', '', $path);
	}

    
	/**
	 * Adds 'media/' to the beginning of path
	 */
	private function addMainDir($path) {
		return SUBDIR_MEDIA . $path;
	}

	
	public function __set_image($value) {
		print_r($value);exit;
		$this->__setIntern('image', $this->removeMainDir($value));
	}

	
	public function __get_image() {
		print_r($this->__getIntern('image'));exit;
		return $this->addMainDir($this->__getIntern('image'));
	}

	
    /**
     * Returns the list of items to make the relation to another model. 
     * So the items returned will be used by the select list.
     * @return array List of items
     */
    public function listSelectSelector() {
    	$items = $this->Find(get_class($this));
    	$selectItems = array();
    	if (is_array($items)) {
			foreach ($items as $i) {
				$thumb = new Thumbnail(null, $i->image, 80, 80, 'c');
				$selectItems[] = array('id' => $i->id, 'value' => $i->makeSelectTitle(), 'image' => $thumb->getThumbnailImagePath());
			}
    	}
    	return $selectItems; 
    }
    
    
} 

?>