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


class BasicTagsModel extends AbstractCodebookModel {
	
	var $package = 'Basic';
	var $icon = 'tag', $table = 'tags';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('articlesTagsConnection')
			->setLabel('Articles')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
		$this->addMetaData(AtributesFactory::create('newsTagsConnection')
			->setLabel('News')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BasicArticlesModel', 'BasicArticlesTagsModel', 'tag', 'article', 'articlesTagsConnection'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelationMany('BasicNewsModel', 'BasicNewsTagsModel', 'tag', 'news', 'newsTagsConnection'); // define a many:many relation to Tag through BlogTag
    }
    
} 

?>