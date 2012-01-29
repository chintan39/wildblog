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


class BasicNewsModel extends AbstractPagesModel {
	
	var $package = 'Basic';
	var $icon = 'news', $table = 'news';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AtributesFactory::stdPublished());
    	$this->addMetaData(AtributesFactory::stdColorRGBHexa());
    	
		$this->addMetaData(AtributesFactory::create('newsTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BasicTagsModel', 'BasicNewsTagsModel', 'news', 'tag', 'newsTagsConnection', 'newsTagsConnection'); // define a many:many relation to Tag through BlogTag
    }

    
	protected function sortingDefinition() {
		if (Config::Get('BASIC_NEWS_SORTABLE')) {
			$this->sorting = array(new ItemSorting('rank', SORTING_DESC));
		} else {
			$this->sorting = array(new ItemSorting('published', SORTING_DESC));
		}
	}
    
	
	public function getPreview() {
		return trim($this->description) ? $this->description : Utilities::truncate(strip_tags($this->text), 250);
	}

} 

?>