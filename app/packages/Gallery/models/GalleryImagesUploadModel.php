<?php

class GalleryImagesUploadModel extends AbstractVirtualModel {

	var $package = 'Gallery';
	var $icon = 'image';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

    	$this->addMetaData(AbstractAttributesModel::stdTitle());
    	$this->addMetaData(AbstractAttributesModel::stdDescription());
    	$this->addMetaData(AbstractAttributesModel::stdUploadFile()
    		->setUploadDir(Utilities::concatPath(DIR_PROJECT_PATH_MEDIA, isset(Request::$get['dir']) ? Request::$get['dir'] : '')));
    	
    }

    
	/**
	 * Save data.
	 */
	public function Save() {
		parent::Save();
		$newImage = new GalleryImagesModel();
		$newImage->title = $this->title;
		$newImage->description = $this->description;
		$newImage->image = Utilities::path2url($this->uploadedFiles['upload_file']);
		$newImage->active = 1;
		if ($newImage->checkFieldsSelf()) {
			return $newImage->Save();
		} else {
			throw new Exception('The new item cannot be saved.');
		}
	}
} 

?>