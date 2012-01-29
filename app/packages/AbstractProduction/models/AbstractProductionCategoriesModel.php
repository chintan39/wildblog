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


class AbstractProductionCategoriesModel extends AbstractStructuredPagesModel {

	var $package = 'AbstractProduction';
	var $icon = 'product_category', $table = 'categories';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$this->addMetaData(AtributesFactory::create('group')
			->setLabel('Properties Group')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
		
		$this->addMetaData(AtributesFactory::create('categoriesProductsConnection')
			->setLabel('Products')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation($this->package . 'PropertiesGroupsModel', 'group', 'id'); // define a 1:many relation to manofacturer 
        $this->addCustomRelationMany($this->package . 'ProductsModel', $this->package . 'CategoriesProductsModel', 'category', 'product', 'categoriesProductsConnection'); // define a many:many relation to Tag through BlogTag
	}
	

} 

?>