<?php

class GalleryGalleriesImagesModel extends AbstractDefaultModel {
	
	var $package = 'Gallery';
	var $icon = '', $table = 'galleries_images';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('gallery')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));

		$this->addMetaData(ModelMetaItem::create('image')
			->setSqltype('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex('index'));

		/* 
		 * This label will be set to 1 if this image should be the title image 
		 * of the gallery. Only one image should be set as title image.
		 */
		$this->addMetaData(ModelMetaItem::create('titleimage')
			->setSqltype('tinyint(2) NOT NULL DEFAULT \'0\''));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('GalleryGalleriesModel', 'gallery', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation('GalleryImagesModel', 'image', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>