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