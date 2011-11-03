<?php

class GalleryImagesNewDirModel extends AbstractVirtualModel {

	var $package = 'Gallery';
	var $icon = 'image';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

    	$this->addMetaData(AbstractAttributesModel::stdUrl());
    	
    }

    
	/**
	 * Save data.
	 */
	public function Save() {
		$dir = (isset(Request::$get['dir']) ? Request::$get['dir'] : '');
		mkdir(Utilities::concatPath(DIR_PROJECT_PATH_MEDIA, $dir, $this->url), 0777);
	}
} 

?>