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


class AbstractProductionPropertiesModel extends AbstractPropertiesModel {

	var $package = 'AbstractProduction';
	var $icon = 'product', $table = 'properties';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$this->addMetaData(AtributesFactory::create('product')
			->setLabel('Product')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
		
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('CommodityProductsModel', 'product', 'id'); // define a 1:many relation to category 
	}
	
	protected function propertiesDefinition() {

		$this->loadPropertiesDefinition($this->package . 'PropertiesDefinitionModel', $this->package . 'PropertiesOptionsModel');
		
	}

} 

?>